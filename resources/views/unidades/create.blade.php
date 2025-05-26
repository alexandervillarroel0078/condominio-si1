@extends('layouts.ap')

@can('crear unidades')
@section('content')
<div class="container">
    <h2 class="mb-4">Registrar Nueva Unidad</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('unidades.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}" required>
        </div>

        <div class="mb-3">
            <label for="placa" class="form-label">Placa</label>
            <input type="text" name="placa" class="form-control" value="{{ old('placa') }}">
        </div>

        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control" value="{{ old('marca') }}">
        </div>

        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number" name="capacidad" class="form-control" value="{{ old('capacidad', 1) }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" class="form-select" required>
                <option value="activa" {{ old('estado')=='activa' ? 'selected' : '' }}>Activa</option>
                <option value="inactiva" {{ old('estado')=='inactiva' ? 'selected' : '' }}>Inactiva</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="personas_por_unidad" class="form-label">Personas por Unidad</label>
            <input type="number" name="personas_por_unidad" class="form-control" value="{{ old('personas_por_unidad', 1) }}" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">¿Tiene Mascotas?</label>
            <div>
                <label class="form-check-label me-3">
                    <input type="radio" name="tiene_mascotas" value="1" {{ old('tiene_mascotas')=='1' ? 'checked' : '' }}>
                    Sí
                </label>
                <label class="form-check-label">
                    <input type="radio" name="tiene_mascotas" value="0" {{ old('tiene_mascotas','0')=='0' ? 'checked' : '' }}>
                    No
                </label>
            </div>
        </div>

        <div class="mb-3">
            <label for="vehiculos" class="form-label">Vehículos</label>
            <input type="number" name="vehiculos" class="form-control" value="{{ old('vehiculos', 0) }}" min="0" required>
        </div>

        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="{{ route('unidades.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@endcan
