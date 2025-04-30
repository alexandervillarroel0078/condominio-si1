<?php

namespace App\Traits;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait BitacoraTrait
{
    public function registrarEnBitacora($accion, $id_operacion = null)
{
    try {
        Bitacora::create([
            'user_id'     => Auth::id(),
            'usuario'     => Auth::user()->name,
            'accion' => $accion,
            'fecha_hora' => now(),
            'ip' => request()->ip(),
            'id_operacion' => $id_operacion,
        ]);
    } catch (\Exception $e) {
        Log::error('Error al registrar en bitácora: ' . $e->getMessage());
    }
}

}
