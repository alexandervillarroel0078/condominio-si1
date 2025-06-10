
{{-- resources/views/visitas/index.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestión de Visitas</h2>

    {{-- @can('crear visitas') --}}
    <a href="{{ route('visitas.create') }}" class="btn btn-primary mb-3">Nueva Visita</a>
    {{-- @endcan --}}

    {{-- @can('panel guardia') --}}
    <a href="{{ route('visitas.panel-guardia') }}" class="btn btn-secondary mb-3">Panel Guardia</a>
    {{-- @endcan --}}

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('visitas.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Buscar por código, visitante, CI o residente"
                   value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Visitante</th>
                <th>CI</th>
                <th>Residente</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Placa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($visitas as $visita)
            <tr>
                <td>{{ $visita->id }}</td>
                <td><strong class="text-primary">{{ $visita->codigo }}</strong></td>
                <td>{{ $visita->nombre_visitante }}</td>
                <td>{{ $visita->ci_visitante }}</td>
                <td>
                  {{ $visita->residente
                       ? $visita->residente->nombre_completo
                       : '-' }}
                </td>
                <td>{{ Str::limit($visita->motivo, 30) }}</td>
                <td>
                    @switch($visita->estado)
                        @case('pendiente')
                            <span class="badge bg-warning">Pendiente</span>
                            @break
                        @case('en_curso')
                            <span class="badge bg-info">En Curso</span>
                            @break
                        @case('finalizada')
                            <span class="badge bg-success">Finalizada</span>
                            @break
                        @case('rechazada')
                            <span class="badge bg-danger">Rechazada</span>
                            @break
                    @endswitch
                </td>
                <td>{{ $visita->fecha_inicio->format('d/m/Y H:i') }}</td>
                <td>{{ $visita->fecha_fin->format('d/m/Y H:i') }}</td>
                <td>{{ $visita->placa_vehiculo ?? '-' }}</td>
                <td>
                    
                    {{-- @can('ver visitas') --}}
                    <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-sm btn-info">Ver</a>
                    {{-- @endcan --}}
                    
                    @if($visita->estado == 'pendiente')
                        {{-- @can('editar visitas') --}}
                        <a href="{{ route('visitas.edit', $visita) }}"
                           class="btn btn-sm btn-warning">
                            Editar
                        </a>
                        {{-- @endcan --}}

                        {{-- @can('registrar entrada') --}}
                        <form action="{{ route('visitas.entrada', $visita) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-success"
                                    onclick="return confirm('¿Registrar entrada del visitante?')">
                                Entrada
                            </button>
                        </form>
                        {{-- @endcan --}}

                        {{-- @can('eliminar visitas') --}}
                        <form action="{{ route('visitas.destroy', $visita) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar esta visita?')">
                                Eliminar
                            </button>
                        </form>
                        {{-- @endcan --}}
                    @endif

                    @if($visita->estado == 'en_curso')
                        {{-- @can('registrar salida') --}}
                        <form action="{{ route('visitas.salida', $visita) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-primary"
                                    onclick="return confirm('¿Registrar salida del visitante?')">
                                Salida
                            </button>
                        </form>
                        {{-- @endcan --}}
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">No hay visitas registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $visitas->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection