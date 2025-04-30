{{-- resources/views/empleados/edit.blade.php --}}
@extends('layouts.ap')
@section('content')
<div class="container">
    <h2>Editar Empleado</h2>
    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $empleado->nombre }}" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ $empleado->apellido }}" required>
        </div>
        <div class="mb-3">
            <label for="ci" class="form-label">Cédula de Identidad</label>
            <input type="text" name="ci" class="form-control" value="{{ $empleado->ci }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection