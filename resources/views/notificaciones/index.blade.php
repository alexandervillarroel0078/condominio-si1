@extends('layouts.ap')

@section('content')
<div class="container">
    <h1 class="mb-4">Notificaciones del Sistema</h1>

    {{-- Buscador --}}
    <form method="GET" action="{{ route('notificaciones.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por título o contenido..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    {{-- Tabla --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th><a href="{{ route('notificaciones.index', array_merge(request()->all(), ['sort' => 'titulo', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-white text-decoration-none">Título</a></th>
                <th><a href="{{ route('notificaciones.index', array_merge(request()->all(), ['sort' => 'contenido', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-white text-decoration-none">Contenido</a></th>
                <th><a href="{{ route('notificaciones.index', array_merge(request()->all(), ['sort' => 'tipo', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-white text-decoration-none">Tipo</a></th>
                <th><a href="{{ route('notificaciones.index', array_merge(request()->all(), ['sort' => 'fecha_hora', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-white text-decoration-none">Fecha y Hora</a></th>
                <th><a href="{{ route('notificaciones.index', array_merge(request()->all(), ['sort' => 'residente_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-white text-decoration-none">Residente</a></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notificaciones as $n)
            <tr @if(!$n->leida) class="fw-bold" @endif>
                <td>{{ $n->titulo }}</td>
                <td>{{ $n->contenido }}</td>
                <td>{{ $n->tipo }}</td>
                <td>{{ \Carbon\Carbon::parse($n->fecha_hora)->format('d/m/Y H:i') }}</td>
                <td>{{ $n->residente->nombre ?? '-' }}</td>
                <td>
                    <div class="d-flex gap-2">
                        {{-- Botón VER --}}
                        <form action="{{ route('notificaciones.ver', $n->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-info" title="Ver y redirigir">Ver</button>
                        </form>

                        {{-- Botón Marcar como leída --}}
                        @if(!$n->leida)
                        <form action="{{ route('notificaciones.marcarLeida', $n->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-secondary" title="Marcar como leída">Leído</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No se encontraron notificaciones.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center">
        {{ $notificaciones->appends(request()->query())->links() }}
    </div>
</div>
@endsection
