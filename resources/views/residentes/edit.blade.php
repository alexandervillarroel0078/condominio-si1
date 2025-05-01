@extends('layouts.ap')
@can('editar residentes')
@section('content')
<div class="container">
    <h2 class="mb-4">Editar Residente</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('residentes.update', $residente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $residente->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $residente->apellido) }}" required>
        </div>

        <div class="mb-3">
            <label for="ci" class="form-label">Cédula de Identidad (CI)</label>
            <input type="text" name="ci" class="form-control" value="{{ old('ci', $residente->ci) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $residente->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="tipo_residente" class="form-label">Tipo de Residente</label>
            <select name="tipo_residente" class="form-select" required>
                <option value="">-- Seleccionar --</option>
                <option value="Propietario" {{ old('tipo_residente', $residente->tipo_residente) == 'Propietario' ? 'selected' : '' }}>Propietario</option>
                <option value="Inquilino" {{ old('tipo_residente', $residente->tipo_residente) == 'Inquilino' ? 'selected' : '' }}>Inquilino</option>
                <option value="Otro" {{ old('tipo_residente', $residente->tipo_residente) == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('residentes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@endcan