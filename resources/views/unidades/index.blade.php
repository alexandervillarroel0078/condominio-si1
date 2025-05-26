@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestión de Unidades</h2>

    {{-- @can('crear unidades') --}}
    <a href="{{ route('unidades.create') }}" class="btn btn-primary mb-3">Nueva Unidad</a>
    {{-- @endcan --}}


    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('unidades.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por código, placa o marca" value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Placa</th>
                <th>Marca</th>
                <th>Capacidad</th>
                <th>Estado</th>
                <th>Personas</th>
                <th>Mascotas</th>
                <th>Vehículos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unidades as $unidad)
            <tr>
                <td>{{ $unidad->id }}</td>
                <td>{{ $unidad->codigo }}</td>
                <td>{{ $unidad->placa ?? '-' }}</td>
                <td>{{ $unidad->marca ?? '-' }}</td>
                <td>{{ $unidad->capacidad }}</td>
                <td>{{ ucfirst($unidad->estado) }}</td>
                <td>{{ $unidad->personas_por_unidad }}</td>
                <td>{{ $unidad->tiene_mascotas ? 'Sí' : 'No' }}</td>
                <td>{{ $unidad->vehiculos }}</td>
                <td>
                  {{--  @can('editar unidades')--}}
                    <a href="{{ route('unidades.edit', $unidad) }}" class="btn btn-sm btn-warning">Editar</a>
                   {{-- @endcan--}}

                  {{--  @can('eliminar unidades')--}}
                    <form action="{{ route('unidades.destroy', $unidad) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta unidad?')">
                            Eliminar
                        </button>
                    </form>
                   {{-- @endcan--}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No hay unidades registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $unidades->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection
