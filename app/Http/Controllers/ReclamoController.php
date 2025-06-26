<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\BitacoraTrait;

class ReclamoController extends Controller
{
    use BitacoraTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->residente_id) {
            // Residente ve sólo sus reclamos
            $reclamos = Reclamo::where('residente_id', $user->residente_id)
                            ->orderBy('fechaCreacion', 'Desc')->get();
        } elseif ($user->empleado_id) {
            // Empleado ve sólo las multas que él registró
            $reclamos = Reclamo::where('empleado_id', $user->empleado_id)
                            ->orderBy('fechaCreacion', 'Desc')->get();
        } else {
            // Administrador ve todas
            $reclamos = Reclamo::orderByRaw("FIELD(estado,'pendiente','abierto','resuelto')")
                            ->orderBy('fechaCreacion', 'desc')->get();
        }

        return view('reclamos.index', compact('reclamos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reclamos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo'        => 'required|in:reclamo,sugerencia',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'adjunto'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only(['tipo','titulo','descripcion']);
        // archivo adjunto opcional
        if ($request->hasFile('adjunto')) {
            $data['adjunto'] = $request->file('adjunto')
                                      ->store('reclamos/adjuntos','public');
        }

        // asignar al usuario actual, sea residente o empleado
        $user = Auth::user();
        if ($user->residente_id) {
            $data['residente_id'] = $user->residente_id;
        } elseif ($user->empleado_id) {
            $data['empleado_id'] = $user->empleado_id;
        }

        // estado inicial "pendiente"
        $reclamo = Reclamo::create($data);

        // Determinar nombre del usuario afectado
        $nombreUsuario = $reclamo->residente
            ? $reclamo->residente->nombre_completo
            : ($reclamo->empleado->nombre_completo ?? 'N/D');

        $this->registrarEnBitacora( "Usuario {$nombreUsuario} Solicito un reclamo/sugerencia ID:{$reclamo->id}", $reclamo->id);

        return redirect()
            ->route('reclamos.index')
            ->with('success', 'Reclamo/sugerencia enviado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reclamo $reclamo)
    {
        // Al verlo, si estaba pendiente lo marcamos como abierto solo para administradores
        $user = Auth::user();
        if (Auth::check() && ! $user->residente_id && ! $user->empleado_id){
            if ($reclamo->estado === 'pendiente') {
                $reclamo->estado = 'abierto';
                $reclamo->save();
            }
        }

        return view('reclamos.show', compact('reclamo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reclamo $reclamo)
    {
        return view('reclamos.edit', compact('reclamo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, Reclamo $reclamo)
    {
        $request->validate([
            'tipo'        => 'required|in:reclamo,sugerencia',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'adjunto'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Si viene un nuevo adjunto, lo borramos y guardamos el nuevo
        if ($request->hasFile('adjunto')) {
            if ($reclamo->adjunto) {
                Storage::disk('public')->delete($reclamo->adjunto);
            }
            $reclamo->adjunto = $request->file('adjunto')
                                       ->store('reclamos/adjuntos','public');
        }

        // Actualizamos campos
        $reclamo->tipo        = $request->tipo;
        $reclamo->titulo      = $request->titulo;
        $reclamo->descripcion = $request->descripcion;
        $reclamo->save();

        // Determinar nombre del usuario afectado
        $nombreUsuario = $reclamo->residente
            ? $reclamo->residente->nombre_completo
            : ($reclamo->empleado->nombre_completo ?? 'N/D');

        $this->registrarEnBitacora( "Usuario {$nombreUsuario} Actualizo su reclamo/sugerencia ID:{$reclamo->id}", $reclamo->id);

        return redirect()
            ->route('reclamos.index')
            ->with('success', 'Reclamo/Sugerencia actualizado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function respuesta(Request $request, Reclamo $reclamo)
    {
        $request->validate([
            'respuesta' => 'required|string',
        ]);
        $reclamo->respuesta = $request->respuesta;
        $reclamo->estado    = 'resuelto';
        $reclamo->save();

        // Determinar nombre del usuario afectado
        $nombreUsuario = $reclamo->residente
            ? $reclamo->residente->nombre_completo
            : ($reclamo->empleado->nombre_completo ?? 'N/D');

        $this->registrarEnBitacora( "Respondio a {$nombreUsuario} su reclamo/sugerencia ID:{$reclamo->id}", $reclamo->id);

        return redirect()
            ->route('reclamos.show', $reclamo->id)
            ->with('success', 'Respuesta guardada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reclamo $reclamo)
    {
        // Si tiene adjunto, lo borramos del disco
        if ($reclamo->adjunto) {
            Storage::disk('public')->delete($reclamo->adjunto);
        }
        // Determinar nombre del usuario afectado
         $nombreUsuario = $reclamo->residente
            ? $reclamo->residente->nombre_completo
            : ($reclamo->empleado->nombre_completo ?? 'N/D');

        // Eliminamos el registro
        $reclamo->delete();

        // Registramos en bitácora si usas ese trait
        $this->registrarEnBitacora( "Usuario {$nombreUsuario} Borro su reclamo/sugerencia ID:{$reclamo->id}", $reclamo->id);

        return redirect()
            ->route('reclamos.index')
            ->with('success', 'Reclamo/Sugerencia eliminado correctamente.');
    }
}
