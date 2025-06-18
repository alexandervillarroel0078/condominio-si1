<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\CategoriaInventario;
use App\Models\AreaComun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
public function index(Request $request)
{
    $request->validate([
        'fecha_inicio' => 'nullable|date',
        'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
    ]);

    $query = Inventario::with('categoria', 'responsable', 'areaComun');

    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('fecha_adquisicion', [
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
    }

    if ($request->filled('categoria_id')) {
        $query->where('categoria_id', $request->categoria_id);
    }

    if ($request->filled('area_comun_id')) {
        $query->where('area_comun_id', $request->area_comun_id);
    }

    if ($request->has('bajo_valor')) {
        $query->where('valor_estimado', '<', 100);
    }

    if ($request->has('por_vencer')) {
        $hoy = now();
        $query->whereRaw("(DATEDIFF(DATE_ADD(fecha_adquisicion, INTERVAL vida_util YEAR), ?) <= 60)", [$hoy]);
    }

    $inventarios = $query->get();
    $categorias = CategoriaInventario::all();
    $areas = AreaComun::all();

    return view('inventario.index', compact('inventarios', 'categorias', 'areas'));
}

    public function create()
    {
        $categorias = CategoriaInventario::all();
        $areas = AreaComun::all();
        return view('inventario.create', compact('categorias', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria_id' => 'required|exists:categoria_inventarios,id',
        ]);

        Inventario::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? 'disponible',
            'fecha_adquisicion' => now(),

            'tipo_adquisicion' => $request->tipo_adquisicion,
            'valor_estimado' => $request->valor_estimado,
            'vida_util' => $request->vida_util,
            'valor_residual' => $request->valor_residual,
            'fecha_baja' => $request->fecha_baja,
            'motivo_baja' => $request->motivo_baja,
            'ubicacion' => $request->ubicacion,
            'categoria_id' => $request->categoria_id,
            'user_id' => Auth::id(),
            'area_comun_id' => $request->area_comun_id,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Inventario registrado correctamente.');
    }

    public function edit(Inventario $inventario)
    {
        $categorias = CategoriaInventario::all();
        $areas = AreaComun::all();
        return view('inventario.edit', compact('inventario', 'categorias', 'areas'));
    }

    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria_id' => 'required|exists:categoria_inventarios,id',
        ]);

        $inventario->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? 'disponible',
            'fecha_adquisicion' => $request->fecha_adquisicion,
            'tipo_adquisicion' => $request->tipo_adquisicion,
            'valor_estimado' => $request->valor_estimado,
            'vida_util' => $request->vida_util,
            'valor_residual' => $request->valor_residual,
            'fecha_baja' => $request->fecha_baja,
            'motivo_baja' => $request->motivo_baja,
            'ubicacion' => $request->ubicacion,
            'categoria_id' => $request->categoria_id,
            'area_comun_id' => $request->area_comun_id,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Inventario actualizado correctamente.');
    }

    public function destroy(Inventario $inventario)
    {
        $inventario->delete();

        return redirect()->route('inventario.index')->with('success', 'Inventario eliminado.');
    }

    public function show($id)
    {
        $inventario = Inventario::with('categoria', 'areaComun', 'user')->findOrFail($id);
        return view('inventario.show', compact('inventario'));
    }
}
