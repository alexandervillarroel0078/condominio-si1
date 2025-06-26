{{-- resources/views/seguridad/create.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('seguridad.index') }}">
                    @if(auth()->user()->can('administrar-seguridad'))
                        Administrar Seguridad
                    @elseif(auth()->user()->can('crear-registro-seguridad'))
                        Panel de Seguridad
                    @else
                        Mis Reportes
                    @endif
                </a>
            </li>
            <li class="breadcrumb-item active">
                @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                    Reportar Incidente
                @else
                    Nuevo Registro
                @endif
            </li>
        </ol>
    </nav>

    {{-- Título con Icono Dinámico --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; font-size: 24px;">
                            🚨
                        </div>
                    @else
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; font-size: 24px;">
                            🛡️
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="mb-1">
                        @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                            Reportar Incidente
                        @else
                            Nuevo Registro de Seguridad
                        @endif
                    </h2>
                    <p class="text-muted mb-0">
                        @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                            Reporta cualquier situación que requiera atención
                        @elseif(auth()->user()->can('crear-registro-seguridad'))
                            Crea un nuevo registro de ronda, incidente o reporte
                        @else
                            Administra los registros de seguridad del condominio
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Por favor corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario Principal --}}
    <form action="{{ route('seguridad.store') }}" method="POST" id="registroForm">
        @csrf
        
        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8 col-md-12">
                {{-- Card Principal --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-edit me-2"></i>
                            <h5 class="mb-0">Información del Registro</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            {{-- Tipo de Registro --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    Tipo de Registro *
                                </label>
                                
                                @if(count($tiposPermitidos) === 1)
                                    {{-- Si solo puede crear un tipo, mostrar como readonly --}}
                                    <input type="hidden" name="tipo" value="{{ $tiposPermitidos[0] }}">
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-warning fs-6 text-dark">
                                            🚨 Incidente
                                        </span>
                                        <small class="text-muted d-block mt-1">Solo puedes reportar incidentes</small>
                                    </div>
                                @else
                                    {{-- Selector con opciones visuales --}}
                                    <div class="row g-2">
                                        @foreach($tiposPermitidos as $tipo)
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="tipo" 
                                                           id="tipo_{{ $tipo }}" 
                                                           value="{{ $tipo }}"
                                                           {{ old('tipo') == $tipo ? 'checked' : '' }}
                                                           required>
                                                    <label class="form-check-label" for="tipo_{{ $tipo }}">
                                                        <div class="d-flex align-items-center">
                                                            @switch($tipo)
                                                                @case('ronda')
                                                                    <div class="me-3">
                                                                        <span class="badge bg-info">🏃</span>
                                                                    </div>
                                                                    <div>
                                                                        <strong>Ronda de Vigilancia</strong>
                                                                        <small class="d-block text-muted">Registro de patrullaje y verificación</small>
                                                                    </div>
                                                                    @break
                                                                @case('incidente')
                                                                    <div class="me-3">
                                                                        <span class="badge bg-warning">🚨</span>
                                                                    </div>
                                                                    <div>
                                                                        <strong>Incidente de Seguridad</strong>
                                                                        <small class="d-block text-muted">Situación que requiere atención</small>
                                                                    </div>
                                                                    @break
                                                                @case('reporte')
                                                                    <div class="me-3">
                                                                        <span class="badge bg-success">📋</span>
                                                                    </div>
                                                                    <div>
                                                                        <strong>Reporte de Turno</strong>
                                                                        <small class="d-block text-muted">Informe general del período</small>
                                                                    </div>
                                                                    @break
                                                            @endswitch
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Prioridad --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                    Prioridad *
                                </label>
                                
                                @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                                    {{-- Residentes: Prioridad fija en media --}}
                                    <input type="hidden" name="prioridad" value="media">
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-warning text-dark fs-6">🟡 Media</span>
                                        <small class="text-muted d-block mt-1">Los incidentes se clasifican automáticamente</small>
                                    </div>
                                @else
                                    {{-- Personal de seguridad y admin: Pueden elegir --}}
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="prioridad" id="prioridad_baja" value="baja" {{ old('prioridad') == 'baja' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="prioridad_baja">
                                            🟢 Baja
                                        </label>

                                        <input type="radio" class="btn-check" name="prioridad" id="prioridad_media" value="media" {{ old('prioridad', 'media') == 'media' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="prioridad_media">
                                            🟡 Media
                                        </label>

                                        <input type="radio" class="btn-check" name="prioridad" id="prioridad_alta" value="alta" {{ old('prioridad') == 'alta' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="prioridad_alta">
                                            🔴 Alta
                                        </label>
                                    </div>
                                @endif
                            </div>

                            {{-- Ubicación --}}
                            <div class="col-12">
                                <label for="ubicacion" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                    Ubicación *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('ubicacion') is-invalid @enderror" 
                                           id="ubicacion"
                                           name="ubicacion" 
                                           placeholder="Ej: Lobby principal, Parqueadero, Piscina, Apartamento 301..."
                                           value="{{ old('ubicacion') }}"
                                           required>
                                    @error('ubicacion')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Especifica la ubicación exacta donde ocurrió la situación
                                </small>
                            </div>

                            {{-- Descripción --}}
                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-bold">
                                    <i class="fas fa-align-left text-info me-1"></i>
                                    Descripción *
                                </label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion"
                                          name="descripcion" 
                                          rows="4" 
                                          placeholder="Describe detalladamente lo que ocurrió, incluyendo fecha, hora y cualquier detalle relevante..."
                                          required>{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Proporciona todos los detalles posibles para una mejor atención
                                </small>
                            </div>

                            {{-- Observaciones Adicionales --}}
                            <div class="col-12">
                                <label for="observaciones" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note text-secondary me-1"></i>
                                    Observaciones Adicionales
                                    <span class="badge bg-secondary ms-1">Opcional</span>
                                </label>
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          id="observaciones"
                                          name="observaciones" 
                                          rows="3" 
                                          placeholder="Información adicional, testigos, acciones tomadas, etc...">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Cualquier información adicional que consideres importante
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna Lateral --}}
            <div class="col-lg-4 col-md-12">
                {{-- Card de Ayuda --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fas fa-question-circle text-info me-2"></i>
                            ¿Necesitas Ayuda?
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                            {{-- Ayuda para Residentes --}}
                            <div class="mb-3">
                                <h6 class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Reportar Incidentes
                                </h6>
                                <small class="text-muted">
                                    Reporta cualquier situación de seguridad que requiera atención del personal de seguridad.
                                </small>
                            </div>
                            
                            <div class="alert alert-info py-2">
                                <small>
                                    <strong>Ejemplos:</strong><br>
                                    • Ruidos extraños<br>
                                    • Personas sospechosas<br>
                                    • Daños en instalaciones<br>
                                    • Problemas de iluminación
                                </small>
                            </div>
                        @else
                            {{-- Ayuda para Personal de Seguridad --}}
                            <div class="mb-3">
                                <h6 class="text-primary">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Tipos de Registro
                                </h6>
                            </div>
                            
                            <div class="mb-3">
                                <strong class="text-info">🏃 Ronda:</strong>
                                <small class="d-block text-muted">Registro de patrullaje rutinario</small>
                            </div>
                            
                            <div class="mb-3">
                                <strong class="text-warning">🚨 Incidente:</strong>
                                <small class="d-block text-muted">Situación que requiere atención inmediata</small>
                            </div>
                            
                            <div class="mb-3">
                                <strong class="text-success">📋 Reporte:</strong>
                                <small class="d-block text-muted">Informe general del turno</small>
                            </div>
                        @endif
                        
                        <hr>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Registro automático: {{ now()->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Card de Información del Usuario --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fas fa-user text-primary me-2"></i>
                            Información del Registro
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Registrado por:</strong>
                            <div class="text-muted">{{ auth()->user()->name }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Fecha y hora:</strong>
                            <div class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ now()->format('d/m/Y') }}
                                <br>
                                <i class="fas fa-clock me-1"></i>
                                {{ now()->format('H:i') }}
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Origen:</strong>
                            <div>
                                @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                                    <span class="badge bg-secondary">🏠 Residente</span>
                                @else
                                    <span class="badge bg-primary">🚪 Personal de Seguridad</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                                    Enviar Reporte
                                @else
                                    Crear Registro
                                @endif
                            </button>
                            
                            <a href="{{ route('seguridad.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                Cancelar
                            </a>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Los campos marcados con * son obligatorios
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection