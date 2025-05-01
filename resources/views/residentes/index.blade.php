@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Lista de Residentes</h2>

    @can('crear residentes')
    <a href="{{ route('residentes.create') }}" class="btn btn-primary mb-3">Nuevo Residente</a>
    @endcan

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('residentes.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, apellido o CI" value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>CI</th>
                <th>Email</th>
                <th>Tipo de Residente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($residentes as $residente)
            <tr>
                <td>{{ $residente->id }}</td>
                <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                <td>{{ $residente->ci }}</td>
                <td>{{ $residente->email }}</td>
                <td>{{ $residente->tipo_residente }}</td>
                <td>
                @can('editar residentes')
                    <a href="{{ route('residentes.edit', $residente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    @endcan

                    @can('eliminar residentes')
                    <form action="{{ route('residentes.destroy', $residente->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este residente?')">Eliminar</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay residentes registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $residentes->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection