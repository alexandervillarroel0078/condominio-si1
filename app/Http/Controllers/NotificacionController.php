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

        // Filtro por residente si el usuario tiene uno asignado
        $user = Auth::user();
        if ($user && $user->residente_id) {
            $query->where('residente_id', $user->residente_id);
        }

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
}
