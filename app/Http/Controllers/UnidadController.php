<?php
// app/Http/Controllers/UnidadController.php

namespace App\Http\Controllers;

use App\Models\Unidad;
use App\Models\Residente;
use App\Http\Requests\UnidadRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
        ->orderBy('id', 'asc')     // ← cambio aquí
        ->paginate(10)
        ->appends(['search' => $search]);

        return view('unidades.index', compact('unidades'));
    }

    /**
     * Muestra el formulario para crear una nueva unidad.
     */
    public function create(): View
    {
        // Cargamos todos los residentes para el selector
        $residentes = Residente::orderBy('apellido')->get();
        return view('unidades.create', compact('residentes'));
    }

    /**
     * Almacena la nueva unidad en la base de datos.
     */
    public function store(UnidadRequest $request): RedirectResponse
    {
        Unidad::create($request->validated());
        return redirect()
            ->route('unidades.index')
            ->with('success', 'Unidad creada correctamente.');
    }

    /**
     * Muestra el formulario de edición de una unidad.
     */
    public function edit(Unidad $unidad): View
    {
        // Cargamos los residentes para el selector, y la unidad a editar
        $residentes = Residente::orderBy('apellido')->get();
        return view('unidades.edit', compact('unidad', 'residentes'));
    }

    /**
     * Actualiza los datos de la unidad.
     */
    public function update(UnidadRequest $request, Unidad $unidad): RedirectResponse
    {
        $unidad->update($request->validated());
        return redirect()
            ->route('unidades.index')
            ->with('success', 'Unidad actualizada correctamente.');
    }

    /**
     * Elimina una unidad.
     */
    public function destroy(Unidad $unidad): RedirectResponse
    {
        $unidad->delete();
        return back()->with('success','Unidad eliminada.');
    }
}
