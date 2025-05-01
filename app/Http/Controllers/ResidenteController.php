<?php

namespace App\Http\Controllers;

use App\Models\Residente;
use Illuminate\Http\Request;
use App\Traits\BitacoraTrait;

class ResidenteController extends Controller
{
    use BitacoraTrait;

    public function index(Request $request)
    {
        $search = $request->input('search');

        $residentes = Residente::when($search, function ($query, $search) {
            $query->where('nombre', 'like', "%$search%")
                ->orWhere('apellido', 'like', "%$search%")
                ->orWhere('ci', 'like', "%$search%");
        })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('residentes.index', compact('residentes'));
    }

    public function create()
    {
        return view('residentes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:residentes',
            'email' => 'required|email|unique:residentes',
            'tipo_residente' => 'required|string|max:100',
        ]);

        $residente = Residente::create($validated);
        $this->registrarEnBitacora('Residente creado', $residente->id);

        return redirect()->route('residentes.index')->with('success', 'Residente registrado correctamente.');
    }

    public function show(Residente $residente)
    {
        return view('residentes.show', compact('residente'));
    }

    public function edit(Residente $residente)
    {
        return view('residentes.edit', compact('residente'));
    }

    public function update(Request $request, Residente $residente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:residentes,ci,' . $residente->id,
            'email' => 'required|email|unique:residentes,email,' . $residente->id,
            'tipo_residente' => 'required|string|max:100',
        ]);

        $residente->update($validated);
        $this->registrarEnBitacora('Residente actualizado', $residente->id);

        return redirect()->route('residentes.index')->with('success', 'Residente actualizado correctamente.');
    }

    public function destroy(Residente $residente)
    {
        $residente->delete();
        $this->registrarEnBitacora('Residente eliminado', $residente->id);

        return redirect()->route('residentes.index')->with('success', 'Residente eliminado correctamente.');
    }
}
