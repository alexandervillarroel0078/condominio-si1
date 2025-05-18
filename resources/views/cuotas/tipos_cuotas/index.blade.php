@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Tipos de Cuotas</h2>

    <a href="{{ route('tipos-cuotas.create') }}" class="btn btn-primary mb-3">Nuevo Tipo de Cuota</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Frecuencia</th>
                <th>Editable</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipos as $tipo)
            <tr>
                <td>{{ $tipo->id }}</td>
                <td>{{ $tipo->nombre }}</td>
                <td>{{ ucfirst($tipo->frecuencia) }}</td>
                <td>{{ $tipo->editable ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('tipos-cuotas.edit', $tipo->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('tipos-cuotas.destroy', $tipo->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este tipo de cuota?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
