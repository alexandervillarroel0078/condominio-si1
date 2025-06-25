<?php

namespace App\Http\Controllers;

use App\Models\RegistroSeguridad;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegistroSeguridadController extends Controller
{
    protected $rol;
    protected $permisos;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $this->rol = $user->roles->pluck('name')->join(', ');
            
            // OBTENER PERMISOS DESDE LA BASE DE DATOS
            $this->permisos = $this->obtenerPermisosUsuario($user);
            
            return $next($request);
        });
    }

    // MÉTODO PARA OBTENER PERMISOS DESDE LA BD
    private function obtenerPermisosUsuario($user)
    {
        // Obtener permisos del usuario a través de sus roles
        $permisos = DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->join('model_has_roles', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', get_class($user))
            ->pluck('permissions.name')
            ->toArray();

        // También obtener permisos directos del usuario (si los hay)
        $permisosDirectos = DB::table('model_has_permissions')
            ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
            ->where('model_has_permissions.model_id', $user->id)
            ->where('model_has_permissions.model_type', get_class($user))
            ->pluck('permissions.name')
            ->toArray();

        return array_unique(array_merge($permisos, $permisosDirectos));
    }

    // VERIFICAR PERMISOS DINÁMICAMENTE
    private function tienePermiso($permiso)
    {
        return in_array($permiso, $this->permisos);
    }

    // VERIFICAR MÚLTIPLES PERMISOS (OR)
    private function tieneAlgunPermiso($permisos)
    {
        return !empty(array_intersect($permisos, $this->permisos));
    }

    // LISTA DE REGISTROS CON CONTROL DE PERMISOS
    public function index(Request $request): View
    {
        $user = Auth::user();

        // VERIFICAR PERMISOS ESPECÍFICOS DE SEGURIDAD
        if ($this->tienePermiso('administrar-seguridad')) {
            // 🔧 ADMIN: Ve TODOS los registros de seguridad
            $titulo = "Administrar Seguridad";
            $query = RegistroSeguridad::with(['usuario', 'resueltoPor']);
            
        } elseif ($this->tienePermiso('ver-registros-seguridad')) {
            // 👔 DIRECTIVA: Ve todos los registros (solo lectura)
            $titulo = "Registros de Seguridad";
            $query = RegistroSeguridad::with(['usuario', 'resueltoPor']);
            
        } elseif ($this->tienePermiso('crear-registro-seguridad')) {
            // 🚪 PERSONAL DE SEGURIDAD: Ve sus registros + incidentes de residentes
            $titulo = "Panel de Seguridad";
            $query = RegistroSeguridad::with(['usuario', 'resueltoPor'])
                        ->where(function($q) use ($user) {
                            $q->where('user_id', $user->id) // Sus propios registros
                            ->orWhere('origen', 'residente'); // + Reportes de residentes
                        });
                        
        } elseif ($this->tienePermiso('reportar-incidentes')) {
            // 🏠 RESIDENTE: Solo SUS propios reportes
            $titulo = "Mis Reportes";
            $query = RegistroSeguridad::with(['usuario', 'resueltoPor'])
                        ->where('user_id', $user->id)
                        ->where('origen', 'residente');
        } else {
            // Sin permisos de seguridad
            abort(403, 'No tienes permisos para ver registros de seguridad');
        }

        // 🔧 INICIALIZAR VARIABLE DE BÚSQUEDA
        $search = null;

        // Aplicar filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search; // ← AQUÍ SE DEFINE LA VARIABLE
            
            $query->where(function($q) use ($search) {
                $q->where('ubicacion', 'LIKE', "%{$search}%")
                ->orWhere('descripcion', 'LIKE', "%{$search}%")
                ->orWhere('tipo', 'LIKE', "%{$search}%")
                ->orWhere('estado', 'LIKE', "%{$search}%")
                ->orWhereHas('usuario', function($subQ) use ($search) {
                    $subQ->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        // Filtrar por tipo si se especifica
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por estado si se especifica
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $registros = $query->orderBy('fecha_hora', 'desc')->paginate(15);

        // 🔧 USAR VARIABLE CORRECTAMENTE EN BITÁCORA
        $this->registrarBitacora(
            'LISTAR_REGISTROS_SEGURIDAD',
            $search ? "Búsqueda con término: {$search}" : "Listado consultado"
        );
        
        return view('seguridad.index', compact('registros', 'titulo'));
    }

    // CREAR REGISTROS CON CONTROL DE PERMISOS
    public function create(): View
    {
        $user = Auth::user();
        
        if ($this->tienePermiso('administrar-seguridad')) {
            // 🔧 ADMIN: Puede crear cualquier tipo de registro
            $tiposPermitidos = ['ronda', 'incidente', 'reporte'];
            
        } elseif ($this->tienePermiso('crear-registro-seguridad')) {
            // 🚪 PERSONAL DE SEGURIDAD: Puede crear cualquier tipo
            $tiposPermitidos = ['ronda', 'incidente', 'reporte'];
            
        } elseif ($this->tienePermiso('reportar-incidentes')) {
            // 🏠 RESIDENTE: Solo puede reportar incidentes
            $tiposPermitidos = ['incidente'];
        } else {
            // Sin permisos para crear
            abort(403, 'No tienes permisos para crear registros de seguridad');
        }

        $this->registrarBitacora(
            'ACCESO_CREAR_REGISTRO',
            "Acceso al formulario de creación"
        );
        
        return view('seguridad.create', compact('tiposPermitidos'));
    }

    // GUARDAR REGISTROS CON CONTROL DE PERMISOS
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'tipo' => 'required|in:ronda,incidente,reporte',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta',
        ]);

        // DETERMINAR ORIGEN Y VALIDACIONES SEGÚN PERMISOS
        if ($this->tienePermiso('reportar-incidentes') && !$this->tienePermiso('crear-registro-seguridad')) {
            // 🏠 RESIDENTE: Solo incidentes con valores fijos
            if ($request->tipo !== 'incidente') {
                return redirect()->back()
                    ->with('error', 'Solo puedes reportar incidentes')
                    ->withInput();
            }
            
            $origen = 'residente';
            $estado = 'pendiente';
            $prioridad = 'media'; // Forzar prioridad media
            
        } else {
            // 🚪 PERSONAL DE SEGURIDAD o 🔧 ADMIN
            $origen = 'seguridad';
            $estado = ($request->tipo === 'incidente') ? 'pendiente' : 'resuelto';
            $prioridad = $request->prioridad;
        }

        $registro = RegistroSeguridad::create([
            'user_id' => $user->id,
            'tipo' => $request->tipo,
            'origen' => $origen,
            'fecha_hora' => Carbon::now(),
            'ubicacion' => $request->ubicacion,
            'descripcion' => $request->descripcion,
            'prioridad' => $prioridad,
            'estado' => $estado,
            'observaciones' => $request->observaciones,
        ]);

        $this->registrarBitacora(
            'CREAR_REGISTRO_SEGURIDAD',
            "Registro creado - Tipo: {$registro->tipo}, Ubicación: {$registro->ubicacion}",
            $registro->id
        );

        return redirect()->route('seguridad.show', $registro)
            ->with('success', 'Registro de seguridad creado correctamente.');
    }

    // VER DETALLES CON CONTROL DE ACCESO
    public function show($id): View
    {
        $user = Auth::user();
        $registro = RegistroSeguridad::findOrFail($id);
        
        // CONTROL DE ACCESO SEGÚN PERMISOS
        if ($this->tienePermiso('administrar-seguridad') || $this->tienePermiso('ver-registros-seguridad')) {
            // 🔧 ADMIN o 👔 DIRECTIVA: Pueden ver CUALQUIER registro
            // Sin restricciones
        } elseif ($this->tienePermiso('crear-registro-seguridad')) {
            // 🚪 PERSONAL DE SEGURIDAD: Sus registros + incidentes de residentes
            if ($registro->user_id !== $user->id && $registro->origen !== 'residente') {
                abort(403, 'Este registro no te pertenece');
            }
        } elseif ($this->tienePermiso('reportar-incidentes')) {
            // 🏠 RESIDENTE: Solo SUS propios reportes
            if ($registro->user_id !== $user->id) {
                abort(403, 'Este registro no te pertenece');
            }
        } else {
            // Sin permisos
            abort(403, 'No tienes permisos para ver este registro');
        }
        
        $registro->load(['usuario', 'resueltoPor']);

        $this->registrarBitacora(
            'VER_REGISTRO_SEGURIDAD',
            "Consulta de registro - Tipo: {$registro->tipo}, ID: {$registro->id}",
            $registro->id
        );

        return view('seguridad.show', compact('registro'));
    }

    // EDITAR REGISTROS CON CONTROL DE PERMISOS
    public function edit($id)
    {
        $user = Auth::user();
        $registro = RegistroSeguridad::findOrFail($id);
        
        // Solo permitir editar registros pendientes
        if (!$registro->puedeSerEditado()) {
            return redirect()->route('seguridad.show', $registro)
                ->with('error', 'Solo se pueden editar registros pendientes');
        }

        // CONTROL DE ACCESO SEGÚN PERMISOS
        if ($this->tienePermiso('administrar-seguridad')) {
            // 🔧 ADMIN: Puede editar CUALQUIER registro
            $tiposPermitidos = ['ronda', 'incidente', 'reporte'];
        } elseif ($this->tienePermiso('crear-registro-seguridad')) {
            // 🚪 PERSONAL DE SEGURIDAD: Solo sus propios registros
            if ($registro->user_id !== $user->id) {
                abort(403, 'Este registro no te pertenece');
            }
            $tiposPermitidos = ['ronda', 'incidente', 'reporte'];
        } elseif ($this->tienePermiso('reportar-incidentes')) {
            // 🏠 RESIDENTE: Solo sus propios incidentes
            if ($registro->user_id !== $user->id) {
                abort(403, 'Este registro no te pertenece');
            }
            $tiposPermitidos = ['incidente'];
        } else {
            // Sin permisos para editar
            abort(403, 'No tienes permisos para editar registros');
        }

        $this->registrarBitacora(
            'ACCESO_EDITAR_REGISTRO',
            "Acceso al formulario de edición - ID: {$registro->id}",
            $registro->id
        );
        
        return view('seguridad.edit', compact('registro', 'tiposPermitidos'));
    }

    // ACTUALIZAR REGISTROS CON CONTROL DE PERMISOS
    public function update(Request $request, $id): RedirectResponse
    {
        $user = Auth::user();
        $registro = RegistroSeguridad::findOrFail($id);
        
        // Solo permitir actualizar registros pendientes
        if (!$registro->puedeSerEditado()) {
            return redirect()->route('seguridad.show', $registro)
                ->with('error', 'Solo se pueden editar registros pendientes');
        }

        // CONTROL DE ACCESO SEGÚN PERMISOS
        if ($this->tienePermiso('reportar-incidentes') && !$this->tienePermiso('administrar-seguridad')) {
            // 🏠 RESIDENTE: Solo SUS propios reportes
            if ($registro->user_id !== $user->id) {
                abort(403, 'Este registro no te pertenece');
            }
        }

        $request->validate([
            'tipo' => 'required|in:ronda,incidente,reporte',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta',
        ]);

        $registro->update([
            'tipo' => $request->tipo,
            'ubicacion' => $request->ubicacion,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'observaciones' => $request->observaciones,
        ]);

        $this->registrarBitacora(
            'ACTUALIZAR_REGISTRO_SEGURIDAD',
            "Registro actualizado - Tipo: {$registro->tipo}, Ubicación: {$registro->ubicacion}",
            $registro->id
        );

        return redirect()->route('seguridad.show', $registro)
            ->with('success', 'Registro actualizado correctamente');
    }

    // ELIMINAR REGISTROS CON CONTROL DE PERMISOS
    public function destroy($id): RedirectResponse
    {
        $user = Auth::user();
        $registro = RegistroSeguridad::findOrFail($id);
        
        // CONTROL DE ACCESO SEGÚN PERMISOS
        if ($this->tienePermiso('administrar-seguridad')) {
            // 🔧 ADMIN: Puede eliminar CUALQUIER registro
            // Sin restricciones
        } else {
            // Otros no pueden eliminar
            abort(403, 'No tienes permisos para eliminar registros');
        }

        // Guardar datos para bitácora
        $tipoRegistro = $registro->tipo;
        $ubicacionRegistro = $registro->ubicacion;
        $descripcionRegistro = $registro->descripcion;

        $this->registrarBitacora(
            'ELIMINAR_REGISTRO_SEGURIDAD',
            "Eliminado - Tipo: {$tipoRegistro}, Ubicación: {$ubicacionRegistro}",
            $registro->id
        );

        $registro->delete();

        return redirect()->route('seguridad.index')
            ->with('success', 'Registro eliminado correctamente');
    }
    // MARCAR COMO EN REVISIÓN - PERSONAL DE SEGURIDAD Y ADMIN
    public function marcarEnRevision(Request $request, $id): RedirectResponse
    {
        $registro = RegistroSeguridad::findOrFail($id);
        
        // 🚪 Solo PERSONAL DE SEGURIDAD y 🔧 ADMIN pueden marcar en revisión
        if (!$this->tieneAlgunPermiso(['crear-registro-seguridad', 'administrar-seguridad'])) {
            return redirect()->back()->with('error', 'No tienes permisos para cambiar el estado del registro');
        }
        
        if (!$registro->puedeMarcarseEnRevision()) {
            return redirect()->back()->with('error', 'Este registro no puede marcarse en revisión');
        }

        $request->validate([
            'observaciones_revision' => 'nullable|string|max:500'
        ]);

        $registro->marcarComoEnRevision(
            Auth::id(), 
            $request->observaciones_revision
        );

        // Mensaje más corto y conciso para bitácora
        $ubicacionCorta = substr($registro->ubicacion, 0, 30);
        $descripcionBitacora = "ID:{$registro->id} - {$registro->tipo} en {$ubicacionCorta}";

        $this->registrarBitacora(
            'EN_REVISION',
            $descripcionBitacora,
            $registro->id
        );

        return redirect()->route('seguridad.show', $registro)
            ->with('success', 'Registro marcado como en revisión');
    }
    // RESOLVER INCIDENTE - SOLO PERSONAL DE SEGURIDAD Y ADMIN
    public function resolver(Request $request, $id): RedirectResponse
    {
        $registro = RegistroSeguridad::findOrFail($id);
        
        // 🚪 Solo PERSONAL DE SEGURIDAD y 🔧 ADMIN pueden resolver
        if (!$this->tieneAlgunPermiso(['crear-registro-seguridad', 'administrar-seguridad'])) {
            return redirect()->back()->with('error', 'No tienes permisos para resolver incidentes');
        }
        
        if ($registro->estado === 'resuelto') {
            return redirect()->back()->with('error', 'Este registro ya está resuelto');
        }

        $request->validate([
            'observaciones_resolucion' => 'nullable|string'
        ]);

        $registro->marcarComoResuelto(
            Auth::id(), 
            $request->observaciones_resolucion
        );

        $this->registrarBitacora(
            'RESOLVER_INCIDENTE',
            "Incidente resuelto - Tipo: {$registro->tipo}, Ubicación: {$registro->ubicacion}",
            $registro->id
        );

        return redirect()->route('seguridad.show', $registro)
            ->with('success', 'Incidente marcado como resuelto');
    }

    // REPORTAR INCIDENTE RÁPIDO - SOLO RESIDENTES
    public function reportarIncidente(): View
    {
        // 🏠 Solo RESIDENTES pueden usar esta función
        if (!$this->tienePermiso('reportar-incidentes')) {
            abort(403, 'No tienes permisos para reportar incidentes');
        }
        
        return view('seguridad.reportar-incidente');
    }

    // MIS REPORTES - SOLO RESIDENTES
    public function misReportes(): View
    {
        // 🏠 Solo RESIDENTES pueden ver sus reportes
        if (!$this->tienePermiso('reportar-incidentes')) {
            abort(403, 'No tienes permisos para ver reportes');
        }
        
        $user = Auth::user();
        $reportes = RegistroSeguridad::where('user_id', $user->id)
                    ->where('origen', 'residente')
                    ->with(['resueltoPor'])
                    ->orderBy('fecha_hora', 'desc')
                    ->paginate(10);

        return view('seguridad.mis-reportes', compact('reportes'));
    }

    // Método privado para registrar en bitácora
    private function registrarBitacora($accion, $descripcion, $id_operacion = null)
    {
        // Acortar el mensaje si es muy largo para evitar el error
        $mensajeCompleto = $accion . ' - ' . $descripcion;
        if (strlen($mensajeCompleto) > 250) {
            $mensajeCompleto = substr($mensajeCompleto, 0, 247) . '...';
        }
        
        Bitacora::create([
            'user_id' => Auth::id(),
            'accion' => $mensajeCompleto,
            'fecha_hora' => Carbon::now(),
            'id_operacion' => $id_operacion,
            'ip' => request()->ip(),
        ]);
    }
}