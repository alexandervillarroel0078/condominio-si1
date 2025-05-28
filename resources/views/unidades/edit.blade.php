{{-- resources/views/unidades/edit.blade.php --}}
@extends('layouts.ap')

{{-- @can('editar unidades') --}}
@section('content')
<div class="container">
    <h2 class="mb-4">Editar Unidad</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('unidades.update', $unidad) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Código --}}
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text"
                   name="codigo"
                   id="codigo"
                   class="form-control"
                   value="{{ old('codigo', $unidad->codigo) }}"
                   required>
        </div>

        {{-- Placa --}}
        <div class="mb-3">
            <label for="placa" class="form-label">Placa</label>
            <input type="text"
                   name="placa"
                   id="placa"
                   class="form-control"
                   value="{{ old('placa', $unidad->placa) }}">
        </div>

        {{-- Marca --}}
        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text"
                   name="marca"
                   id="marca"
                   class="form-control"
                   value="{{ old('marca', $unidad->marca) }}">
        </div>

        {{-- Capacidad --}}
        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number"
                   name="capacidad"
                   id="capacidad"
                   class="form-control"
                   value="{{ old('capacidad', $unidad->capacidad) }}"
                   min="1"
                   required>
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="activa"  {{ old('estado', $unidad->estado) == 'activa'  ? 'selected' : '' }}>Activa</option>
                <option value="inactiva"{{ old('estado', $unidad->estado) == 'inactiva'? 'selected' : '' }}>Inactiva</option>
            </select>
        </div>

        {{-- Personas por unidad --}}
        <div class="mb-3">
            <label for="personas_por_unidad" class="form-label">Personas por Unidad</label>
            <input type="number"
                   name="personas_por_unidad"
                   id="personas_por_unidad"
                   class="form-control"
                   value="{{ old('personas_por_unidad', $unidad->personas_por_unidad) }}"
                   min="1"
                   required>
        </div>

        {{-- Tiene mascotas --}}
        <div class="mb-3">
            <label class="form-label">¿Tiene Mascotas?</label>
            <div>
                <label class="form-check-label me-3">
                    <input type="radio"
                           name="tiene_mascotas"
                           value="1"
                           {{ old('tiene_mascotas', $unidad->tiene_mascotas) == '1' ? 'checked' : '' }}>
                    Sí
                </label>
                <label class="form-check-label">
                    <input type="radio"
                           name="tiene_mascotas"
                           value="0"
                           {{ old('tiene_mascotas', $unidad->tiene_mascotas) == '0' ? 'checked' : '' }}>
                    No
                </label>
            </div>
        </div>

        {{-- Vehículos --}}
        <div class="mb-3">
            <label for="vehiculos" class="form-label">Vehículos</label>
            <input type="number"
                   name="vehiculos"
                   id="vehiculos"
                   class="form-control"
                   value="{{ old('vehiculos', $unidad->vehiculos) }}"
                   min="0"
                   required>
        </div>

        {{-- Residente asignado --}}
        <div class="mb-3">
            <label for="residente_id" class="form-label">Residente</label>
            <select name="residente_id" id="residente_id" class="form-select">
                <option value="">-- Seleccionar Residente --</option>
                @foreach($residentes as $res)
                    <option value="{{ $res->id }}"
                        {{ old('residente_id', $unidad->residente_id) == $res->id ? 'selected' : '' }}>
                        {{ $res->nombreCompleto }}
                    </option>
                @endforeach
            </select>
            @error('residente_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Botones --}}
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('unidades.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
{{-- @endcan --}}
