<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cuota;
use Illuminate\Http\Request;
use App\Traits\BitacoraTrait;

class PagoController extends Controller
{
    use BitacoraTrait;

    public function index(Request $request)
    {
        $query = \App\Models\Pago::with(['cuota.residente', 'user']);

        // Filtro por búsqueda (nombre del residente o ID de cuota)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('cuota.residente', function ($res) use ($search) {
                    $res->where('nombre', 'like', "%$search%")
                        ->orWhere('apellido', 'like', "%$search%")
                        ->orWhere('unidad', 'like', "%$search%");
                })->orWhereHas('cuota', function ($cu) use ($search) {
                    $cu->where('id', 'like', "%$search%");
                });
            });
        }

        // Filtro por método de pago
        if ($request->filled('metodo')) {
            $query->where('metodo', $request->metodo);
        }

        // Filtro por tiempo
        switch ($request->filtro_tiempo) {
            case 'fecha':
                if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {
                    $query->whereBetween('fecha_pago', [$request->fecha_desde, $request->fecha_hasta]);
                }
                break;

            case 'mes':
                if ($request->filled('mes')) {
                    $query->whereMonth('fecha_pago', date('m', strtotime($request->mes)))
                        ->whereYear('fecha_pago', date('Y', strtotime($request->mes)));
                }
                break;

            case 'semana':
                if ($request->filled('semana')) {
                    $week = explode('-W', $request->semana);
                    $startOfWeek = \Carbon\Carbon::now()->setISODate($week[0], $week[1])->startOfWeek();
                    $endOfWeek = \Carbon\Carbon::now()->setISODate($week[0], $week[1])->endOfWeek();
                    $query->whereBetween('fecha_pago', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);
                }
                break;

            case 'anio':
                if ($request->filled('anio')) {
                    $query->whereYear('fecha_pago', $request->anio);
                }
                break;
        }

        $pagos = $query->orderByDesc('fecha_pago')->paginate(10);

        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $cuotas = Cuota::where('estado', '!=', 'pagado')->get();
        return view('pagos.create', compact('cuotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cuota_id' => 'required|exists:cuotas,id',
            'monto_pagado' => 'required|numeric|min:1',
            'fecha_pago' => 'required|date',
            'metodo' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        $cuota = Cuota::findOrFail($request->cuota_id);

        // Crear el pago
        $pago = Pago::create([
            'cuota_id' => $cuota->id,
            'monto_pagado' => $request->monto_pagado,
            'fecha_pago' => $request->fecha_pago,
            'metodo' => $request->metodo,
            'observacion' => $request->observacion,
            'user_id' => auth()->id(),
        ]);

        // Si el pago cubre el monto total de la cuota, actualizar el estado
        if ($request->monto_pagado >= $cuota->monto) {
            $cuota->estado = 'pagado';
            $cuota->save();
        }
        $this->registrarEnBitacora('Pago registrado', $pago->id);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado exitosamente.');
    }
}
