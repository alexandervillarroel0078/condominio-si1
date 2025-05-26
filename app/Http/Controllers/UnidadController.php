<?php
// app/Http/Controllers/UnidadController.php

namespace App\Http\Controllers;

use App\Models\Unidad;
use App\Http\Requests\UnidadRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

    public function create(): View
    {
        return view('unidades.create');
    }

    public function store(UnidadRequest $request): RedirectResponse
    {
        Unidad::create($request->validated());
        return redirect()
               ->route('unidades.index')
               ->with('success','Unidad creada correctamente.');
    }

    public function edit(Unidad $unidad): View
    {
        return view('unidades.edit', compact('unidad'));
    }

    public function update(UnidadRequest $request, Unidad $unidad): RedirectResponse
    {
        $unidad->update($request->validated());
        return redirect()
               ->route('unidades.index')
               ->with('success','Unidad actualizada correctamente.');
    }

    public function destroy(Unidad $unidad): RedirectResponse
    {
        $unidad->delete();
        return back()->with('success','Unidad eliminada.');
    }
}
