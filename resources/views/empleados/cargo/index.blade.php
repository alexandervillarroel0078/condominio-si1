@extends('layouts.ap')

@section('content')
<div class="container">
    <h2>Lista de Cargos</h2>
    <a href="{{ route('cargos.create') }}" class="btn btn-primary mb-3">Nuevo Cargo</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cargo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cargos as $cargo)
            <tr>
                <td>{{ $cargo->id }}</td>
                <td>{{ $cargo->cargo }}</td>
                <td>
                    @if($cargo->estado)
                    <span class="badge bg-success">Activo</span>
                    @else
                    <span class="badge bg-danger">Inactivo</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('cargos.edit', $cargo->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('cargos.destroy', $cargo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este cargo?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection