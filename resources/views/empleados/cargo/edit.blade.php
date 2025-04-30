@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Empleado</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $empleado->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $empleado->apellido) }}" required>
        </div>

        <div class="mb-3">
            <label for="ci" class="form-label">Cédula de Identidad (CI)</label>
            <input type="text" name="ci" class="form-control" value="{{ old('ci', $empleado->ci) }}" required>
        </div>

        <div class="mb-3">
            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', $empleado->fecha_ingreso) }}">
        </div>

        <div class="mb-3">
            <label for="cargo_empleado_id" class="form-label">Cargo</label>
            <select name="cargo_empleado_id" class="form-select" required>
                <option value="">-- Seleccionar Cargo --</option>
                @foreach ($cargos as $cargo)
                    <option value="{{ $cargo->id }}" {{ old('cargo_empleado_id', $empleado->cargo_empleado_id) == $cargo->id ? 'selected' : '' }}>
                        {{ $cargo->cargo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" value="1" {{ old('estado', $empleado->estado) == '1' ? 'checked' : '' }}>
                <label class="form-check-label">Activo</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" value="0" {{ old('estado', $empleado->estado) == '0' ? 'checked' : '' }}>
                <label class="form-check-label">Inactivo</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
