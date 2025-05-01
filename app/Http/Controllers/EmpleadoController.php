<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use App\Models\CargoEmpleado;
use App\Traits\BitacoraTrait;

class EmpleadoController extends Controller

{
    use BitacoraTrait;
     
    public function index(Request $request)
    {
        $search = $request->input('search');

        $empleados = Empleado::with('cargo')
            ->when($search, function ($query, $search) {
                $query->where('nombre', 'like', "%$search%")
                    ->orWhere('apellido', 'like', "%$search%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $cargos = CargoEmpleado::where('estado', true)->get();
        return view('empleados.create', compact('cargos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:empleados',
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|boolean',
            'cargo_empleado_id' => 'required|exists:cargo_empleados,id',
        ]);

        $empleado = Empleado::create($validated);
        $this->registrarEnBitacora('Empleado creado', $empleado->id);

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado correctamente.');
    }

    // Mostrar un empleado específico
    public function show(Empleado $empleado)
    {
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $cargos = CargoEmpleado::where('estado', true)->get();
        return view('empleados.edit', compact('empleado', 'cargos'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:empleados,ci,' . $empleado->id,
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|boolean',
            'cargo_empleado_id' => 'required|exists:cargo_empleados,id',
        ]);

        $empleado->update($validated);
        $this->registrarEnBitacora('Empleado actualizado', $empleado->id);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }


    // Eliminar un empleado
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        $this->registrarEnBitacora('Empleado eliminado', $empleado->id);

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
