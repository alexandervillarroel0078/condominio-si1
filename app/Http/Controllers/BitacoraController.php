<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::latest()->paginate(10);
        return view('bitacora.index', compact('bitacoras'));
    }

    public function show($id)
    {
        $bitacora = Bitacora::findOrFail($id);
        return view('bitacora.show', compact('bitacora'));
    }

    public function destroy($id)
    {
        $bitacora = Bitacora::findOrFail($id);
        $bitacora->delete();

        return redirect()->route('bitacora.index')->with('success', 'Bitácora eliminada correctamente');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'accion' => 'required|string|max:100',
        ]);

        Bitacora::create([
            'descripcion' => $request->input('descripcion'),
            'accion' => $request->input('accion'),
            'ip' => $request->ip(), // IP del cliente
        ]);

        return redirect()->route('bitacora.index')->with('success', 'Bitácora registrada correctamente');
    }
}
