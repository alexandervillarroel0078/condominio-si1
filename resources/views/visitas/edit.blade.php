
{{-- resources/views/visitas/edit.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Visita</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('visitas.update', $visita) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Residente --}}
        <div class="mb-3">
            <label for="residente_id" class="form-label">Residente</label>
            <select name="residente_id" id="residente_id" class="form-select" required>
                <option value="">-- Seleccionar Residente --</option>
                @foreach($residentes as $residente)
                    <option value="{{ $residente->id }}"
                        {{ old('residente_id', $visita->residente_id) == $residente->id ? 'selected' : '' }}>
                        {{ $residente->nombre_completo }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nombre del Visitante --}}
        <div class="mb-3">
            <label for="nombre_visitante" class="form-label">Nombre del Visitante</label>
            <input type="text"
                   name="nombre_visitante"
                   id="nombre_visitante"
                   class="form-control"
                   value="{{ old('nombre_visitante', $visita->nombre_visitante) }}"
                   required>
        </div>

        {{-- CI del Visitante --}}
        <div class="mb-3">
            <label for="ci_visitante" class="form-label">CI del Visitante</label>
            <input type="text"
                   name="ci_visitante"
                   id="ci_visitante"
                   class="form-control"
                   value="{{ old('ci_visitante', $visita->ci_visitante) }}"
                   required>
        </div>

        {{-- Motivo --}}
        <div class="mb-3">
            <label for="motivo" class="form-label">Motivo de la Visita</label>
            <select name="motivo" id="motivo" class="form-select" required>
                <option value="">-- Seleccionar Motivo --</option>
                <option value="Visita familiar" {{ old('motivo', $visita->motivo) == 'Visita familiar' ? 'selected' : '' }}>Visita familiar</option>
                <option value="Servicio técnico" {{ old('motivo', $visita->motivo) == 'Servicio técnico' ? 'selected' : '' }}>Servicio técnico</option>
                <option value="Delivery" {{ old('motivo', $visita->motivo) == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                <option value="Visita social" {{ old('motivo', $visita->motivo) == 'Visita social' ? 'selected' : '' }}>Visita social</option>
                <option value="Entrega de documentos" {{ old('motivo', $visita->motivo) == 'Entrega de documentos' ? 'selected' : '' }}>Entrega de documentos</option>
                <option value="Reunión de trabajo" {{ old('motivo', $visita->motivo) == 'Reunión de trabajo' ? 'selected' : '' }}>Reunión de trabajo</option>
                <option value="Otro" {{ old('motivo', $visita->motivo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                
                {{-- Si el motivo actual no está en las opciones predefinidas, mostrarlo --}}
                @php
                    $motivosPredefinidos = ['Visita familiar', 'Servicio técnico', 'Delivery', 'Visita social', 'Entrega de documentos', 'Reunión de trabajo', 'Otro'];
                    $motivoActual = old('motivo', $visita->motivo);
                @endphp
                
                @if($motivoActual && !in_array($motivoActual, $motivosPredefinidos))
                    <option value="{{ $motivoActual }}" selected>{{ $motivoActual }}</option>
                @endif
            </select>
        </div>

        {{-- Fecha y Hora de Inicio --}}
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
            <input type="datetime-local"
                   name="fecha_inicio"
                   id="fecha_inicio"
                   class="form-control"
                   value="{{ old('fecha_inicio', $visita->fecha_inicio->format('Y-m-d\TH:i')) }}"
                   required>
        </div>

        {{-- Fecha y Hora de Fin --}}
        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha y Hora de Fin</label>
            <input type="datetime-local"
                   name="fecha_fin"
                   id="fecha_fin"
                   class="form-control"
                   value="{{ old('fecha_fin', $visita->fecha_fin->format('Y-m-d\TH:i')) }}"
                   required>
        </div>

        {{-- Placa del Vehículo --}}
        <div class="mb-3">
            <label for="placa_vehiculo" class="form-label">Placa del Vehículo (Opcional)</label>
            <input type="text"
                   name="placa_vehiculo"
                   id="placa_vehiculo"
                   class="form-control"
                   value="{{ old('placa_vehiculo', $visita->placa_vehiculo) }}"
                   placeholder="Ej: ABC-123">
        </div>

        {{-- Información no editable --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">Información de la Visita</h6>
                        <p class="mb-1"><strong>Código:</strong> <span class="text-primary">{{ $visita->codigo }}</span></p>
                        <p class="mb-1"><strong>Estado:</strong> 
                            <span class="badge bg-warning">{{ ucfirst($visita->estado) }}</span>
                        </p>
                        <p class="mb-0"><strong>Creada:</strong> {{ $visita->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="mb-3">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Actualizar Visita
            </button>
            <a href="{{ route('visitas.show', $visita) }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <a href="{{ route('visitas.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list"></i> Ver Todas las Visitas
            </a>
        </div>
    </form>
</div>
@endsection