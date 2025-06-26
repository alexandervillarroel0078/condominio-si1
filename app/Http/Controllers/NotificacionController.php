<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Notificacion::with('residente');

        $user = Auth::user();
        $residenteId = $user->residente_id;

        // Mostrar notificaciones globales o dirigidas al residente actual
        $query->where(function ($q) use ($residenteId) {
            $q->whereNull('residente_id')
              ->orWhere('residente_id', $residenteId);
        });

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('contenido', 'like', "%{$search}%");
            });
        }

        // Orden dinámico por columnas
        $sortable = ['titulo', 'contenido', 'tipo', 'fecha_hora'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'fecha_hora';
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';

        $notificaciones = $query->orderBy($sortBy, $order)
                                ->paginate(10)
                                ->appends($request->all());

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $residenteId = Auth::user()->residente_id;

        if (is_null($notificacion->residente_id) || $notificacion->residente_id == $residenteId) {
            $notificacion->update(['leida' => true]);
        }

        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }

    public function ver($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $residenteId = Auth::user()->residente_id;

        if (is_null($notificacion->residente_id) || $notificacion->residente_id == $residenteId) {
            $notificacion->update(['leida' => true]);

            if ($notificacion->ruta) {
                return redirect($notificacion->ruta);
            }
        }

        return redirect()->route('notificaciones.index');
    }
}
