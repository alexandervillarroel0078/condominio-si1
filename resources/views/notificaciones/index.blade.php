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
            </tr>
        </thead>
        <tbody>
            @forelse($notificaciones as $n)
            <tr>
                <td>{{ $n->titulo }}</td>
                <td>{{ $n->contenido }}</td>
                <td>{{ $n->tipo }}</td>
                <td>{{ $n->fecha_hora }}</td>
                <td>{{ $n->residente->nombre ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No se encontraron notificaciones.</td>
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
