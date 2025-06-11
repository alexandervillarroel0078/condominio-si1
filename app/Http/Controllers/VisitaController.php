<?php


namespace App\Http\Controllers;

use App\Models\Visita;
use App\Models\Residente;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VisitaController extends Controller
{
    protected $rol;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->rol = Auth::user()->roles->pluck('name')->join(', ');
            return $next($request);
        });
    }

    // Lista de visitas (para administrador/guardia)
    public function index(Request $request)
    {
        $user = Auth::user();
        $rol = $this->rol;

        // Filtrar visitas según el rol del usuario
        if ($rol == 'Residente') {
            // Residente solo ve sus propias visitas
            $query = Visita::with(['residente', 'userEntrada', 'userSalida'])
                        ->whereHas('residente', function($q) use ($user) {
                            $q->where('email', $user->email);
                        });
        } else {
            // Admin/Guardia ven todas las visitas
            $query = Visita::with(['residente', 'userEntrada', 'userSalida']);
        }

        // Aplicar filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                $q->where('codigo', 'LIKE', "%{$search}%")
                ->orWhere('nombre_visitante', 'LIKE', "%{$search}%")
                ->orWhere('ci_visitante', 'LIKE', "%{$search}%")
                ->orWhere('placa_vehiculo', 'LIKE', "%{$search}%")
                ->orWhere('motivo', 'LIKE', "%{$search}%")
                ->orWhereHas('residente', function($subQ) use ($search) {
                    $subQ->where('nombre_completo', 'LIKE', "%{$search}%");
                });
            });
        }

        $visitas = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('visitas.index', compact('visitas'));
    }

    // Formulario para crear visita (residente)
    public function create()
    {
        $user = Auth::user();
        $rol = $this->rol;
        
        
        if ($rol == 'Residente') {
            // Residente solo puede crear visitas para sí mismo
            $residentes = Residente::where('email', $user->email)->get();
            if ($residentes->count() == 0) {  
                // Mostrar todos los emails para comparar
                $todosLosEmails = Residente::pluck('email')->toArray();
            } else {
                $residente = $residentes->first();
            }
        } else {
            // Admin puede crear para cualquier residente
            $residentes = Residente::all();
        }     
        return view('visitas.create', compact('residentes'));
    }

    // Guardar nueva visita (residente)
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'residente_id' => 'required|exists:residentes,id',
            'nombre_visitante' => 'required|string|max:255',
            'ci_visitante' => 'required|string|max:20',
            'motivo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'placa_vehiculo' => 'nullable|string|max:20'
        ]);

        // Verificar que el residente solo pueda crear visitas para sí mismo
        $rol = $this->rol;
        if ($rol == 'Residente') {
            $residente = Residente::find($request->residente_id);
            if ($residente->email !== $user->email) {
                return redirect()->back()
                    ->with('error', 'Solo puedes crear visitas para ti mismo')
                    ->withInput();
            }
        }

        $visita = Visita::create([
            'residente_id' => $request->residente_id,
            'nombre_visitante' => $request->nombre_visitante,
            'ci_visitante' => $request->ci_visitante,
            'placa_vehiculo' => $request->placa_vehiculo,
            'motivo' => $request->motivo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'codigo' => $this->generarCodigo(),
            'estado' => 'pendiente'
        ]);

        // Registrar en bitácora
        $this->registrarBitacora(
            'CREAR_VISITA',
            "Visita creada para {$visita->nombre_visitante} (CI: {$visita->ci_visitante}) - Código: {$visita->codigo}",
            $visita->id
        );

        return redirect()->route('visitas.show', $visita)
            ->with('success', 'Visita registrada. Código: ' . $visita->codigo);
    }

    // Ver detalles de visita
    public function show($id)
    {
        $user = Auth::user();
        $visita = Visita::findOrFail($id);
        
        // Control de acceso: residente solo sus visitas
        $rol = $this->rol;
        if ($rol == 'Residente') {
            if ($visita->residente->email !== $user->email) {
                abort(403, 'Esta visita no te pertenece');
            }
        }
        
        $visita->load(['residente', 'userEntrada', 'userSalida']);
        return view('visitas.show', compact('visita'));
    }

    // Formulario para editar visita
    public function edit($id)
    {
        $user = Auth::user();
        $visita = Visita::findOrFail($id);
        
        // Solo permitir editar visitas pendientes
        if ($visita->estado !== 'pendiente') {
            return redirect()->route('visitas.show', $visita)
                ->with('error', 'Solo se pueden editar visitas pendientes');
        }

        // Control de acceso: residente solo sus visitas
        $rol = $this->rol;
        if ($rol == 'Residente') {
            if ($visita->residente->email !== $user->email) {
                abort(403, 'Esta visita no te pertenece');
            }
            $residentes = Residente::where('email', $user->email)->get();
        } else {
            // Admin puede editar cualquier visita
            $residentes = Residente::all();
        }
        
        return view('visitas.edit', compact('visita', 'residentes'));
    }

    // Actualizar visita
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $visita = Visita::findOrFail($id);
        
        // Solo permitir actualizar visitas pendientes
        if ($visita->estado !== 'pendiente') {
            return redirect()->route('visitas.show', $visita)
                ->with('error', 'Solo se pueden editar visitas pendientes');
        }

        // Control de acceso: residente solo sus visitas
        $rol = $this->rol;
        if ($rol == 'Residente') {
            if ($visita->residente->email !== $user->email) {
                abort(403, 'Esta visita no te pertenece');
            }
        }

        $request->validate([
            'residente_id' => 'required|exists:residentes,id',
            'nombre_visitante' => 'required|string|max:255',
            'ci_visitante' => 'required|string|max:20',
            'motivo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'placa_vehiculo' => 'nullable|string|max:20'
        ]);

        $visita->update([
            'residente_id' => $request->residente_id,
            'nombre_visitante' => $request->nombre_visitante,
            'ci_visitante' => $request->ci_visitante,
            'placa_vehiculo' => $request->placa_vehiculo,
            'motivo' => $request->motivo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        // Registrar en bitácora
        $this->registrarBitacora(
            'EDITAR_VISITA',
            "Visita editada - Visitante: {$visita->nombre_visitante}, CI: {$visita->ci_visitante}",
            $visita->id
        );

        return redirect()->route('visitas.show', $visita)
            ->with('success', 'Visita actualizada correctamente');
    }

    // Eliminar visita
    public function destroy($id)
    {
        $user = Auth::user();
        $visita = Visita::findOrFail($id);
        
        // Solo permitir eliminar visitas pendientes o rechazadas
        if (!in_array($visita->estado, ['pendiente', 'rechazada'])) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar una visita en curso o finalizada');
        }

        // Control de acceso: residente solo sus visitas
        $rol = $this->rol;
        if ($rol == 'Residente') {
            if ($visita->residente->email !== $user->email) {
                abort(403, 'Esta visita no te pertenece');
            }
        }

        $nombreVisitante = $visita->nombre_visitante;
        $ciVisitante = $visita->ci_visitante;
        $codigo = $visita->codigo;

        // Registrar eliminación en bitácora antes de eliminar
        $this->registrarBitacora(
            'ELIMINAR_VISITA',
            "Visita eliminada - Visitante: {$nombreVisitante}, CI: {$ciVisitante}, Código: {$codigo}",
            $visita->id
        );

        $visita->delete();

        return redirect()->route('visitas.index')
            ->with('success', 'Visita eliminada correctamente');
    }

    // Mostrar formulario de validación de código
    public function mostrarValidarCodigo()
    {
        $user = Auth::user();
        
        // Solo guardia y admin pueden validar códigos
        $rol = $this->rol;
        if ($rol == 'Residente') {
            abort(403, 'No tienes permisos para validar códigos');
        }
        
        return view('visitas.validar-codigo');
    }

    // Validar código y mostrar datos (guardia)
    public function validarCodigo(Request $request)
    {
        $user = Auth::user();
        
        // Solo guardia y admin pueden validar códigos
        $rol = $this->rol;
        if ($rol == 'Residente') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para validar códigos'
            ]);
        }
        
        $request->validate([
            'codigo' => 'required|string|size:6',
            'ci_visitante' => 'required|string'
        ]);

        $visita = Visita::where('codigo', $request->codigo)
            ->where('ci_visitante', $request->ci_visitante)
            ->where('estado', 'pendiente')
            ->first();

        if (!$visita) {
            // Registrar intento fallido en bitácora
            $this->registrarBitacora(
                'VALIDACION_FALLIDA',
                "Intento de validación fallido - Código: {$request->codigo}, CI: {$request->ci_visitante}"
            );

            return response()->json([
                'success' => false,
                'message' => 'Código incorrecto o CI no coincide'
            ]);
        }

        // Validar horario
        $ahora = Carbon::now();
        if ($ahora < $visita->fecha_inicio || $ahora > $visita->fecha_fin) {
            // Registrar intento fuera de horario
            $this->registrarBitacora(
                'FUERA_HORARIO',
                "Intento de ingreso fuera de horario - Visitante: {$visita->nombre_visitante}, Código: {$request->codigo}",
                $visita->id
            );

            return response()->json([
                'success' => false,
                'message' => 'Fuera del horario autorizado'
            ]);
        }

        // Registrar validación exitosa
        $this->registrarBitacora(
            'VALIDACION_EXITOSA',
            "Código validado correctamente - Visitante: {$visita->nombre_visitante}, CI: {$request->ci_visitante}",
            $visita->id
        );

        return response()->json([
            'success' => true,
            'visita' => [
                'id' => $visita->id,
                'nombre_visitante' => $visita->nombre_visitante,
                'ci_visitante' => $visita->ci_visitante,
                'motivo' => $visita->motivo,
                'residente' => $visita->residente->nombre_completo,
                'placa_vehiculo' => $visita->placa_vehiculo
            ]
        ]);
    }

    // Registrar entrada (guardia)
    public function registrarEntrada(Request $request, $id)
    {
        $user = Auth::user();
        $visita = Visita::findOrFail($id);
        
        // Solo guardia y admin pueden registrar entradas
        $rol = $this->rol;
        if ($rol == 'Residente') {
            return redirect()->back()->with('error', 'No tienes permisos para registrar entradas');
        }
        
        if ($visita->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta visita ya fue procesada');
        }

        $visita->update([
            'estado' => 'en_curso',
            'hora_entrada' => Carbon::now(),
            'user_entrada_id' => Auth::id()
        ]);

        // Registrar entrada en bitácora
        $this->registrarBitacora(
            'REGISTRAR_ENTRADA',
            "Entrada registrada - Visitante: {$visita->nombre_visitante}, CI: {$visita->ci_visitante}",
            $visita->id
        );

        return redirect()->route('visitas.show', $visita)
            ->with('success', 'Entrada registrada correctamente');
    }

    // Registrar salida (guardia)
    public function registrarSalida(Request $request, $id)
    {
        $user = Auth::user();
        $visita = Visita::findOrFail($id);
        
        // Solo guardia y admin pueden registrar salidas
        $rol = $this->rol;
        if ($rol == 'Residente') {
            return redirect()->back()->with('error', 'No tienes permisos para registrar salidas');
        }
        
        if ($visita->estado !== 'en_curso') {
            return redirect()->back()->with('error', 'No se puede registrar salida');
        }

        $visita->update([
            'estado' => 'finalizada',
            'hora_salida' => Carbon::now(),
            'user_salida_id' => Auth::id(),
            'observaciones' => $request->observaciones
        ]);

        // Registrar salida en bitácora
        $this->registrarBitacora(
            'REGISTRAR_SALIDA',
            "Salida registrada - Visitante: {$visita->nombre_visitante}, CI: {$visita->ci_visitante}",
            $visita->id
        );

        return redirect()->route('visitas.show', $visita)
            ->with('success', 'Salida registrada correctamente');
    }

    // Panel de control para guardia
    public function panelGuardia()
    {
        $user = Auth::user();
        
        // Solo guardia y admin pueden acceder al panel
        $rol = $this->rol;
        if ($rol == 'Residente') {
            abort(403, 'No tienes permisos para acceder al Panel de Guardia');
        }
        
        // Solo traer visitas en curso que SÍ tengan hora_entrada registrada
        $visitasEnCurso = Visita::enCurso()
            ->whereNotNull('hora_entrada')
            ->with('residente')
            ->get();
            
        $visitasPendientes = Visita::pendientes()
            ->whereBetween('fecha_inicio', [Carbon::now(), Carbon::now()->addHours(2)])
            ->with('residente')
            ->get();

        return view('visitas.panel-guardia', compact('visitasEnCurso', 'visitasPendientes'));
    }

    // Buscar visitas por código (API para guardia)
    public function buscarPorCodigo(Request $request)
    {
        $user = Auth::user();
        
        // Solo guardia y admin pueden buscar códigos
        $rol = $this->rol;
        if ($rol == 'Residente') {
            return response()->json(['success' => false, 'message' => 'No tienes permisos']);
        }
        
        $visita = Visita::where('codigo', $request->codigo)
            ->with('residente')
            ->first();

        if (!$visita) {
            return response()->json(['success' => false, 'message' => 'Código no encontrado']);
        }

        return response()->json(['success' => true, 'visita' => $visita]);
    }

    // Generar código de 6 dígitos único
    private function generarCodigo()
    {
        do {
            $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Visita::where('codigo', $codigo)->exists());

        return $codigo;
    }

    // Método privado para registrar en bitácora
    private function registrarBitacora($accion, $descripcion, $id_operacion = null)
    {
        Bitacora::create([
            'user_id' => Auth::id(),
            'accion' => $accion . ' - ' . $descripcion,
            'fecha_hora' => Carbon::now(),
            'id_operacion' => $id_operacion,
            'ip' => request()->ip(),
        ]);
    }
}