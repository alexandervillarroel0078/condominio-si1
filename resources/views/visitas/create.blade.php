
@extends('layouts.ap')

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
            
            @if(auth()->user()->roles->pluck('name')->join(', ') == 'Residente')
                {{-- Para residentes: mostrar solo su información como campo de solo lectura --}}
                @if($residentes->count() > 0)
                    <input type="text" 
                           class="form-control" 
                           value="{{ $residentes->first()->nombre_completo }}" 
                           readonly>
                    <input type="hidden" 
                           name="residente_id" 
                           value="{{ $residentes->first()->id }}">
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Solo puedes crear visitas para ti mismo
                    </small>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>¡Atención!</strong> No se encontró tu perfil de residente. 
                        <br>Por favor, contacta al administrador para configurar tu perfil.
                    </div>
                    <script>
                        // Deshabilitar el formulario si no hay residente
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.querySelector('form');
                            const inputs = form.querySelectorAll('input, select, button[type="submit"]');
                            inputs.forEach(input => {
                                if (input.type !== 'button' && !input.classList.contains('btn-secondary')) {
                                    input.disabled = true;
                                }
                            });
                        });
                    </script>
                @endif
            @else
                {{-- Para admin/guardia: permitir seleccionar cualquier residente --}}
                <select name="residente_id" id="residente_id" class="form-select" required>
                    <option value="">-- Seleccionar Residente --</option>
                    @foreach($residentes as $residente)
                        <option value="{{ $residente->id }}"
                            {{ old('residente_id') == $residente->id ? 'selected' : '' }}>
                            {{ $residente->nombre_completo }}
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">
                    <i class="fas fa-users"></i> 
                    Como administrador, puedes crear visitas para cualquier residente
                </small>
            @endif
            
            @error('residente_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Nombre del Visitante --}}
        <div class="mb-3">
            <label for="nombre_visitante" class="form-label">
                <i class="fas fa-user"></i> Nombre del Visitante
            </label>
            <input type="text"
                   name="nombre_visitante"
                   id="nombre_visitante"
                   class="form-control"
                   value="{{ old('nombre_visitante') }}"
                   placeholder="Ingresa el nombre completo del visitante"
                   required>
            @error('nombre_visitante')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- CI del Visitante --}}
        <div class="mb-3">
            <label for="ci_visitante" class="form-label">
                <i class="fas fa-id-card"></i> CI del Visitante
            </label>
            <input type="text"
                   name="ci_visitante"
                   id="ci_visitante"
                   class="form-control"
                   value="{{ old('ci_visitante') }}"
                   placeholder="Ej: 12345678"
                   required>
            @error('ci_visitante')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Motivo --}}
        <div class="mb-3">
            <label for="motivo" class="form-label">
                <i class="fas fa-clipboard-list"></i> Motivo de la Visita
            </label>
            <select name="motivo" id="motivo" class="form-select" required>
                <option value="">-- Seleccionar Motivo --</option>
                <option value="Visita familiar" {{ old('motivo') == 'Visita familiar' ? 'selected' : '' }}>
                    🏠 Visita familiar
                </option>
                <option value="Servicio técnico" {{ old('motivo') == 'Servicio técnico' ? 'selected' : '' }}>
                    🔧 Servicio técnico
                </option>
                <option value="Delivery" {{ old('motivo') == 'Delivery' ? 'selected' : '' }}>
                    📦 Delivery
                </option>
                <option value="Visita social" {{ old('motivo') == 'Visita social' ? 'selected' : '' }}>
                    👥 Visita social
                </option>
                <option value="Entrega de documentos" {{ old('motivo') == 'Entrega de documentos' ? 'selected' : '' }}>
                    📄 Entrega de documentos
                </option>
                <option value="Reunión de trabajo" {{ old('motivo') == 'Reunión de trabajo' ? 'selected' : '' }}>
                    💼 Reunión de trabajo
                </option>
                <option value="Otro" {{ old('motivo') == 'Otro' ? 'selected' : '' }}>
                    ❓ Otro
                </option>
            </select>
            @error('motivo')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Fecha y Hora de Inicio --}}
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">
                <i class="fas fa-calendar-alt"></i> Fecha y Hora de Inicio
            </label>
            <input type="datetime-local"
                   name="fecha_inicio"
                   id="fecha_inicio"
                   class="form-control"
                   value="{{ old('fecha_inicio') }}"
                   @if(auth()->user()->roles->pluck('name')->join(', ') == 'Residente')
                       min="{{ date('Y-m-d\TH:i') }}"
                   @endif
                   required>
            <small class="form-text text-muted">
                @if(auth()->user()->roles->pluck('name')->join(', ') == 'Residente')
                    <i class="fas fa-clock"></i> Solo puedes programar visitas desde la fecha actual
                @else
                    <i class="fas fa-info-circle"></i> Como administrador, puedes programar visitas en cualquier fecha
                @endif
            </small>
            @error('fecha_inicio')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Fecha y Hora de Fin --}}
        <div class="mb-3">
            <label for="fecha_fin" class="form-label">
                <i class="fas fa-calendar-check"></i> Fecha y Hora de Fin
            </label>
            <input type="datetime-local"
                   name="fecha_fin"
                   id="fecha_fin"
                   class="form-control"
                   value="{{ old('fecha_fin') }}"
                   required>
            <small class="form-text text-muted">
                <i class="fas fa-info-circle"></i> 
                La fecha de fin debe ser posterior a la fecha de inicio
            </small>
            @error('fecha_fin')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Placa del Vehículo (Opcional) --}}
        <div class="mb-3">
            <label for="placa_vehiculo" class="form-label">
                <i class="fas fa-car"></i> Placa del Vehículo (Opcional)
            </label>
            <input type="text"
                   name="placa_vehiculo"
                   id="placa_vehiculo"
                   class="form-control"
                   value="{{ old('placa_vehiculo') }}"
                   placeholder="Ej: ABC-123"
                   maxlength="10">
            <small class="form-text text-muted">
                <i class="fas fa-info-circle"></i> 
                Solo completar si el visitante llegará en vehículo
            </small>
            @error('placa_vehiculo')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Información adicional para residentes --}}
        @if(auth()->user()->roles->pluck('name')->join(', ') == 'Residente')
            <div class="alert alert-info">
                <h6><i class="fas fa-lightbulb"></i> Información importante:</h6>
                <ul class="mb-0">
                    <li>Una vez registrada la visita, recibirás un <strong>código de 6 dígitos</strong></li>
                    <li>Comparte este código con tu visitante</li>
                    <li>El visitante debe presentar el código y su CI en la entrada</li>
                    <li>Puedes editar o cancelar la visita mientras esté <span class="badge bg-warning">Pendiente</span></li>
                </ul>
            </div>
        @endif

        {{-- Botones --}}
        <div class="mb-3">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Registrar Visita
            </button>
            <a href="{{ route('visitas.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

@section('scripts')
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
        
        // Formatear placa del vehículo en tiempo real
        const placaInput = document.getElementById('placa_vehiculo');
        placaInput.addEventListener('input', function() {
            let valor = this.value.toUpperCase();
            // Remover caracteres no válidos
            valor = valor.replace(/[^A-Z0-9-]/g, '');
            this.value = valor;
        });
    });
    
    // Validación adicional del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const fechaInicio = new Date(document.getElementById('fecha_inicio').value);
        const fechaFin = new Date(document.getElementById('fecha_fin').value);
        
        if (fechaFin <= fechaInicio) {
            e.preventDefault();
            alert('La fecha de fin debe ser posterior a la fecha de inicio');
            return false;
        }
        
        // Validar que la duración no sea más de 24 horas
        const diferenciaHoras = (fechaFin - fechaInicio) / (1000 * 60 * 60);
        if (diferenciaHoras > 24) {
            if (!confirm('La visita durará más de 24 horas. ¿Estás seguro?')) {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
@endsection
@endsection