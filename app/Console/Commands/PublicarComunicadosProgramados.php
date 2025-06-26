<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comunicado;
use App\Models\Notificacion;
use App\Models\Residente;
use Illuminate\Support\Facades\Log;

class PublicarComunicadosProgramados extends Command
{
    protected $signature = 'comunicados:publicar';
    protected $description = 'Publica comunicados programados y genera notificaciones para residentes';

    public function handle()
    {
        $comunicados = Comunicado::where('fecha_publicacion', '<=', now())
            ->where('notificado', false)
            ->get();

        foreach ($comunicados as $comunicado) {
            // Obtener todos los residentes
            $residentes = Residente::all();

            foreach ($residentes as $residente) {
                Notificacion::create([
                    'titulo' => 'Nuevo Comunicado: ' . $comunicado->titulo,
                    'contenido' => $comunicado->contenido,
                    'tipo' => $comunicado->tipo,
                    'fecha_hora' => now(),
                    'residente_id' => $residente->id,
                ]);
            }

            $comunicado->notificado = true;
            $comunicado->save();

            Log::info("Comunicado publicado automáticamente: {$comunicado->id}");
        }

        return 0;
    }
}
