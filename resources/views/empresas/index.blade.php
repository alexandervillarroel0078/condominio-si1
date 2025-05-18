@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Empresas Externas</h2>

    <a href="{{ route('empresas.create') }}" class="btn btn-primary mb-3">Registrar Nueva Empresa</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filtro de búsqueda --}}
    <div class="container mb-4">
        <div class="card shadow">
            <div class="card-body">
                <form method="GET" action="{{ route('empresas.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Buscar por nombre o servicio</label>
                            <input type="text" name="search" class="form-control" placeholder="Ej: Jardinería, Seguridad" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-outline-primary" type="submit">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabla de empresas --}}
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Servicio</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($empresas as $empresa)
            <tr>
                <td>{{ $empresa->id }}</td>
                <td>{{ $empresa->nombre }}</td>
                <td>{{ $empresa->servicio }}</td>
                <td>{{ $empresa->telefono }}</td>
                <td>{{ $empresa->correo }}</td>
                <td>
                    <a href="{{ route('empresas.show', $empresa->id) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta empresa?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay empresas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $empresas->appends(request()->query())->links() }}
    </div>
</div>
@endsection
