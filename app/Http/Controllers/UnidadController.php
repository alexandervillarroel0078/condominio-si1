<?php


namespace App\Http\Controllers;

use App\Models\Unidad;
use App\Models\Residente;
use App\Models\Bitacora;
use App\Http\Requests\UnidadRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Traits\BitacoraTrait;

class UnidadController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $unidades = Unidad::when($search, function($query, $search) {
                $query->where('codigo', 'like', "%{$search}%")
                      ->orWhere('placa',  'like', "%{$search}%")
                      ->orWhere('marca',  'like', "%{$search}%");
            })
            ->with('residente')
            ->orderBy('id', 'asc') 
            ->paginate(10)
            ->appends(['search' => $search]);
        $this->registrarBitacora(
            'LISTAR_UNIDADES',
            $search ? "Búsqueda de unidades con término: {$search}" : "Listado de unidades consultado"
        );
        return view('unidades.index', compact('unidades'));
    }

    public function create(): View
    {
        $residentes = Residente::orderBy('apellido')->get();
        $this->registrarBitacora(
            'ACCESO_CREAR_UNIDAD',
            "Acceso al formulario de creación de unidad"
        );
        return view('unidades.create', compact('residentes'));
    }

    public function store(UnidadRequest $request): RedirectResponse
    {
        $unidad = Unidad::create($request->validated());
        $this->registrarBitacora(
            'CREAR_UNIDAD',
            "Unidad creada - Código: {$unidad->codigo}, Placa: {$unidad->placa}, Marca: {$unidad->marca}",
            $unidad->id
        );
        return redirect()
            ->route('unidades.index')
            ->with('success', 'Unidad creada correctamente.');
    }

    public function show($id): View
    {
        $unidad = Unidad::with('residente')->findOrFail($id);
        $this->registrarBitacora(
            'VER_UNIDAD',
            "Consulta de detalles de unidad - Código: {$unidad->codigo}",
            $unidad->id
        );

        return view('unidades.show', compact('unidad'));
    }

    public function edit($id): View
    {
        
        $unidad = Unidad::findOrFail($id);
        $residentes = Residente::orderBy('apellido')->get();
        $this->registrarBitacora(
            'ACCESO_EDITAR_UNIDAD',
            "Acceso al formulario de edición - Unidad código: {$unidad->codigo}",
            $unidad->id
        ); 
        return view('unidades.edit', compact('unidad', 'residentes'));
    }

    public function update(UnidadRequest $request, $id): RedirectResponse
    {
        $unidad = Unidad::findOrFail($id);
        
        // GUARDAR DATOS ANTERIORES PARA LA BITÁCORA
        $datosAnteriores = [
            'codigo' => $unidad->codigo,
            'placa' => $unidad->placa,
            'marca' => $unidad->marca,
            'residente_id' => $unidad->residente_id
        ];

        $unidad->update($request->validated());

        // REGISTRAR EN BITÁCORA CON CAMBIOS
        $cambios = [];
        foreach ($request->validated() as $campo => $valorNuevo) {
            if (isset($datosAnteriores[$campo]) && $datosAnteriores[$campo] != $valorNuevo) {
                $cambios[] = "{$campo}: '{$datosAnteriores[$campo]}' → '{$valorNuevo}'";
            }
        }

        $descripcionCambios = empty($cambios) ? 
            "Unidad actualizada sin cambios - Código: {$unidad->codigo}" :
            "Unidad actualizada - Código: {$unidad->codigo}. Cambios: " . implode(', ', $cambios);

        $this->registrarBitacora(
            'ACTUALIZAR_UNIDAD',
            $descripcionCambios,
            $unidad->id
        );
        
        return redirect()
            ->route('unidades.index')
            ->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        $unidad = Unidad::findOrFail($id);
        
        //GUARDAR DATOS ANTES DE ELIMINAR
        $codigoUnidad = $unidad->codigo;
        $placaUnidad = $unidad->placa;
        $marcaUnidad = $unidad->marca;
        $residenteNombre = $unidad->residente ? $unidad->residente->nombre_completo : 'Sin residente';
        $this->registrarBitacora(
            'ELIMINAR_UNIDAD',
            "Código: {$codigoUnidad}, Placa: {$placaUnidad}, Residente: {$residenteNombre}",
            $unidad->id
        );

        $unidad->delete();
        
        return redirect()
            ->route('unidades.index')
            ->with('success', 'Unidad eliminada correctamente.');
    }

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