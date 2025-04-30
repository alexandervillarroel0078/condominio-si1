<?php

namespace App\Http\Controllers;

use App\Models\CargoEmpleado;
use Illuminate\Http\Request;

class CargoEmpleadoController extends Controller
{
    public function index()
    {
        $cargos = CargoEmpleado::all();
        return view('empleados.cargo.index', compact('cargos'));
    }

    public function create()
    {
        return view('empleados.cargo.create');
    }

    public function store(Request $request)
    {
        $request->validate(['cargo' => 'required|string|max:255']);
        CargoEmpleado::create(['cargo' => $request->cargo]);
        return redirect()->route('cargos.index')->with('success', 'Cargo creado correctamente.');
    }

    public function edit($id)
    {
        $cargo = CargoEmpleado::findOrFail($id);
        return view('empleados.cargo.edit', compact('cargo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['cargo' => 'required|string|max:255']);
        $cargo = CargoEmpleado::findOrFail($id);
        $cargo->update(['cargo' => $request->cargo]);
        return redirect()->route('cargos.index')->with('success', 'Cargo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cargo = CargoEmpleado::findOrFail($id);
        $cargo->delete();
        return redirect()->route('cargos.index')->with('success', 'Cargo eliminado correctamente.');
    }
}
