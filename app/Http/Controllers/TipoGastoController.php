<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoGasto;

class TipoGastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = TipoGasto::all();
        return view('tipo_gastos.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipo_gastos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:tipo_gastos,nombre',
        ]);

        TipoGasto::create($request->all());

        return redirect()->route('tipo-gastos.index')->with('success', 'Tipo de gasto creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoGasto $tipoGasto)
    {
        return view('tipo_gastos.edit', compact('tipoGasto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  TipoGasto $tipoGasto)
    {
        $request->validate([
            'nombre' => 'required|unique:tipo_gastos,nombre,' . $tipoGasto->id,
        ]);

        $tipoGasto->update($request->all());

        return redirect()->route('tipo-gastos.index')->with('success', 'Actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoGasto $tipoGasto)
    {
        $tipoGasto->delete();
        return redirect()->route('tipo-gastos.index')->with('success', 'Eliminado correctamente');
    }
}
