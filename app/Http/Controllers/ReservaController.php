<?php

namespace App\Http\Controllers;

use App\Models\AreaComun;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::with(['areaComun', 'residente'])->paginate(10);
        return view('reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areasComunes = AreaComun::all();
        return view('reservas.create', compact('areasComunes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'area_comun_id' => 'required|exists:area_comuns,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'observacion' => 'nullable|string|max:255',
            'monto_total' => 'nullable|numeric|min:0',
        ]);

        // Validar solapamiento de horas para el área y fecha
        $conflict = Reserva::where('area_comun_id', $request->area_comun_id)
            ->where('fecha', $request->fecha)
            ->where(function($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                    ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                    ->orWhere(function($q) use ($request) {
                        $q->where('hora_inicio', '<=', $request->hora_inicio)
                        ->where('hora_fin', '>=', $request->hora_fin);
                    });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['La reserva seleccionada se solapa con otra ya existente.'])->withInput();
        }

        // Obtener el área común para sacar el precio por hora
        $areaComun = AreaComun::findOrFail($request->area_comun_id);

        // Calcular duración en horas (suponiendo formato H:i)
        $horaInicio = \Carbon\Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = \Carbon\Carbon::createFromFormat('H:i', $request->hora_fin);

        $duracionHoras = $horaFin->diffInMinutes($horaInicio) / 60; // duración en horas decimal

        // Calcular monto total
        $montoTotal = $duracionHoras * $areaComun->monto; // monto por hora

        // Obtener usuario autenticado y su residente asociado
        $user = auth()->user();
        if (!$user || !$user->residente) {
            return back()->withErrors(['residente_id' => 'No se encontró el residente asociado al usuario autenticado'])->withInput();
        }

        // Crear reserva con monto total calculado y residente_id
        Reserva::create([
            'area_comun_id' => $request->area_comun_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'observacion' => $request->observacion,
            'estado' => 'pendiente',
            'residente_id' => $user->residente->id,
            'monto_total' => $montoTotal,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva creada correctamente.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        //
    }

    public function horasLibres(Request $request)
    {
        $area_comun_id = $request->query('area_comun_id');
        $fecha = $request->query('fecha');

        if (!$area_comun_id || !$fecha) {
            return response()->json([], 400);
        }

        // Horas posibles (puedes ajustar rango)
        $horasPosibles = [];
        for ($h = 8; $h <= 20; $h++) {
            $horasPosibles[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $horasPosibles[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':30';
        }

        // Obtener reservas para ese día y área
        $reservas = Reserva::where('area_comun_id', $area_comun_id)
                    ->where('fecha', $fecha)
                    ->get();

        // Remover las horas ocupadas (aproximadamente)
        foreach ($reservas as $reserva) {
            $horaInicio = $reserva->hora_inicio;
            $horaFin = $reserva->hora_fin;

            $horasPosibles = array_filter($horasPosibles, function($hora) use ($horaInicio, $horaFin) {
                return !($hora >= $horaInicio && $hora < $horaFin);
            });
        }

        // Reordenar índices
        $horasPosibles = array_values($horasPosibles);

        return response()->json($horasPosibles);
    }
}
