<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\TipoGasto;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::with('tipoGasto')->get();
        $tipos = TipoGasto::all();
        return view('gastos.index', compact('gastos', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_gasto_id' => 'required|exists:tipo_gastos,id',
            'concepto' => 'required',
            'monto' => 'required|numeric',
        ]);

        Gasto::create($request->all());
        return redirect()->route('gastos.index')->with('success', 'Gasto registrado correctamente.');
    }

    public function update(Request $request, Gasto $gasto)
    {
        $request->validate([
            'tipo_gasto_id' => 'required|exists:tipo_gastos,id',
            'concepto' => 'required',
            'monto' => 'required|numeric',
        ]);

        $gasto->update($request->all());
        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado correctamente.');
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado correctamente.');
    }
}
