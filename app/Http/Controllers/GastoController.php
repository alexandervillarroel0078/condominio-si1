<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\TipoGasto;
use Illuminate\Http\Request;
use App\Traits\BitacoraTrait;

class GastoController extends Controller
{
    use BitacoraTrait;
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

        $gasto = Gasto::create($request->all());
        $this->registrarEnBitacora('Gasto registrado', $gasto->id);

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

        $this->registrarEnBitacora('Gasto actualizado', $gasto->id);

        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado correctamente.');
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();

        $this->registrarEnBitacora('Gasto eliminado', $gasto->id);
        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado correctamente.');
    }
}
