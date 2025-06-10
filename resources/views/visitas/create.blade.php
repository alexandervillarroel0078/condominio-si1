{{-- resources/views/visitas/create.blade.php --}}
@extends('layouts.ap')

{{-- @can('crear visitas') --}}
@section('content')
<div class="container">
    <h2 class="mb-4">Registrar Nueva Visita</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('visitas.store') }}" method="POST">
        @csrf

        {{-- Residente --}}
        <div class="mb-3">
            <label for="residente_id" class="form-label">Residente</label>
            <select name="residente_id" id="residente_id" class="form-select" required>
                <option value="">-- Seleccionar Residente --</option>
                @foreach($residentes as $residente)
                    <option value="{{ $residente->id }}"
                        {{ old('residente_id') == $residente->id ? 'selected' : '' }}>
                        {{ $residente->nombre_completo }}
                    </option>
                @endforeach
            </select>
            @error('residente_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Nombre del Visitante --}}
        <div class="mb-3">
            <label for="nombre_visitante" class="form-label">Nombre del Visitante</label>
            <input type="text"
                   name="nombre_visitante"
                   id="nombre_visitante"
                   class="form-control"
                   value="{{ old('nombre_visitante') }}"
                   required>
        </div>

        {{-- CI del Visitante --}}
        <div class="mb-3">
            <label for="ci_visitante" class="form-label">CI del Visitante</label>
            <input type="text"
                   name="ci_visitante"
                   id="ci_visitante"
                   class="form-control"
                   value="{{ old('ci_visitante') }}"
                   required>
        </div>

        {{-- Motivo --}}
        <div class="mb-3">
            <label for="motivo" class="form-label">Motivo de la Visita</label>
            <select name="motivo" id="motivo" class="form-select" required>
                <option value="">-- Seleccionar Motivo --</option>
                <option value="Visita familiar" {{ old('motivo') == 'Visita familiar' ? 'selected' : '' }}>Visita familiar</option>
                <option value="Servicio técnico" {{ old('motivo') == 'Servicio técnico' ? 'selected' : '' }}>Servicio técnico</option>
                <option value="Delivery" {{ old('motivo') == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                <option value="Visita social" {{ old('motivo') == 'Visita social' ? 'selected' : '' }}>Visita social</option>
                <option value="Entrega de documentos" {{ old('motivo') == 'Entrega de documentos' ? 'selected' : '' }}>Entrega de documentos</option>
                <option value="Reunión de trabajo" {{ old('motivo') == 'Reunión de trabajo' ? 'selected' : '' }}>Reunión de trabajo</option>
                <option value="Otro" {{ old('motivo') == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        {{-- Fecha y Hora de Inicio --}}
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
            <input type="datetime-local"
                   name="fecha_inicio"
                   id="fecha_inicio"
                   class="form-control"
                   value="{{ old('fecha_inicio') }}"
                   min="{{ date('Y-m-d\TH:i') }}"
                   required>
        </div>

        {{-- Fecha y Hora de Fin --}}
        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha y Hora de Fin</label>
            <input type="datetime-local"
                   name="fecha_fin"
                   id="fecha_fin"
                   class="form-control"
                   value="{{ old('fecha_fin') }}"
                   required>
        </div>

        {{-- Placa del Vehículo (Opcional) --}}
        <div class="mb-3">
            <label for="placa_vehiculo" class="form-label">Placa del Vehículo (Opcional)</label>
            <input type="text"
                   name="placa_vehiculo"
                   id="placa_vehiculo"
                   class="form-control"
                   value="{{ old('placa_vehiculo') }}"
                   placeholder="Ej: ABC-123">
        </div>

        {{-- Botones --}}
        <button type="submit" class="btn btn-success">Registrar Visita</button>
        <a href="{{ route('visitas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    // Script para validar que fecha_fin sea mayor que fecha_inicio
    document.getElementById('fecha_inicio').addEventListener('change', function() {
        const fechaInicio = this.value;
        const fechaFin = document.getElementById('fecha_fin');
        
        if (fechaInicio) {
            // Agregar 1 hora como mínimo para la fecha fin
            const inicio = new Date(fechaInicio);
            inicio.setHours(inicio.getHours() + 1);
            fechaFin.min = inicio.toISOString().slice(0, 16);
            
            // Si no hay fecha fin o es menor que la nueva fecha mínima, actualizarla
            if (!fechaFin.value || new Date(fechaFin.value) <= new Date(fechaInicio)) {
                fechaFin.value = inicio.toISOString().slice(0, 16);
            }
        }
    });
    
    // Validar al cargar la página si hay valores old()
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        if (fechaInicio) {
            document.getElementById('fecha_inicio').dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
{{-- @endcan --}}