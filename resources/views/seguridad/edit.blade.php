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
            <li class="breadcrumb-item">
                <a href="{{ route('seguridad.show', $registro) }}">
                    Registro #{{ $registro->id }}
                </a>
            </li>
            <li class="breadcrumb-item active">
                Editar
            </li>
        </ol>
    </nav>

    {{-- Título con Estado del Registro --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center flex-wrap">
                <div class="me-3 mb-2 mb-md-0">
                    @switch($registro->tipo)
                        @case('ronda')
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; font-size: 24px;">
                                🏃
                            </div>
                            @break
                        @case('incidente')
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; font-size: 24px;">
                                🚨
                            </div>
                            @break
                        @case('reporte')
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; font-size: 24px;">
                                📋
                            </div>
                            @break
                    @endswitch
                </div>
                <div class="flex-grow-1">
                    <h2 class="mb-1">
                        Editar Registro 
                        <span class="text-muted">#{{ $registro->id }}</span>
                    </h2>
                    <div class="d-flex flex-wrap align-items-center">
                        <span class="badge bg-warning text-dark me-2 mb-1">
                            🔴 {{ strtoupper($registro->estado) }}
                        </span>
                        <span class="badge bg-secondary me-2 mb-1">
                            {{ ucfirst($registro->tipo) }}
                        </span>
                        <small class="text-muted mb-1">
                            <i class="fas fa-clock me-1"></i>
                            {{ $registro->fecha_hora->format('d/m/Y H:i') }}
                        </small>
                    </div>
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

    {{-- Alerta de Solo Edición --}}
    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
        <i class="fas fa-info-circle me-2 fs-4"></i>
        <div>
            <strong>Editando registro existente</strong><br>
            <small>Solo puedes modificar registros que estén en estado pendiente. Una vez resueltos, no podrán ser editados.</small>
        </div>
    </div>

    {{-- Formulario de Edición --}}
    <form action="{{ route('seguridad.update', $registro) }}" method="POST" id="editForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8 col-md-12">
                {{-- Card Principal --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-edit me-2"></i>
                            <h5 class="mb-0">Modificar Información</h5>
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
                                
                                @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                                    {{-- Residentes: Solo incidentes --}}
                                    <input type="hidden" name="tipo" value="incidente">
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-warning fs-6 text-dark">
                                            🚨 Incidente
                                        </span>
                                        <small class="text-muted d-block mt-1">Solo puedes editar incidentes</small>
                                    </div>
                                @else
                                    {{-- Personal de Seguridad y Admin: Pueden cambiar tipo --}}
                                    <div class="row g-2">
                                        @foreach($tiposPermitidos as $tipo)
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="tipo" 
                                                           id="tipo_{{ $tipo }}" 
                                                           value="{{ $tipo }}"
                                                           {{ old('tipo', $registro->tipo) == $tipo ? 'checked' : '' }}
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
                                        <small class="text-muted d-block mt-1">La prioridad se asigna automáticamente</small>
                                    </div>
                                @else
                                    {{-- Personal de seguridad y admin: Pueden cambiar prioridad --}}
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="prioridad" id="prioridad_baja" value="baja" {{ old('prioridad', $registro->prioridad) == 'baja' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="prioridad_baja">
                                            🟢 Baja
                                        </label>

                                        <input type="radio" class="btn-check" name="prioridad" id="prioridad_media" value="media" {{ old('prioridad', $registro->prioridad) == 'media' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="prioridad_media">
                                            🟡 Media
                                        </label>

                                        <input type="radio" class="btn-check" name="prioridad" id="prioridad_alta" value="alta" {{ old('prioridad', $registro->prioridad) == 'alta' ? 'checked' : '' }}>
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
                                           value="{{ old('ubicacion', $registro->ubicacion) }}"
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
                                          required>{{ old('descripcion', $registro->descripcion) }}</textarea>
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
                                          placeholder="Información adicional, testigos, acciones tomadas, etc...">{{ old('observaciones', $registro->observaciones) }}</textarea>
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
                {{-- Card de Información Original --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fas fa-history text-secondary me-2"></i>
                            Información Original
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Registrado por:</strong>
                            <div class="text-muted">{{ $registro->usuario ? $registro->usuario->name : 'Usuario no disponible' }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Fecha original:</strong>
                            <div class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $registro->fecha_hora->format('d/m/Y') }}
                                <br>
                                <i class="fas fa-clock me-1"></i>
                                {{ $registro->fecha_hora->format('H:i') }}
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Origen:</strong>
                            <div>
                                @if($registro->origen == 'residente')
                                    <span class="badge bg-secondary">🏠 Residente</span>
                                @else
                                    <span class="badge bg-primary">🚪 Personal de Seguridad</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Estado:</strong>
                            <div>
                                <span class="badge bg-warning text-dark">🔴 {{ strtoupper($registro->estado) }}</span>
                            </div>
                        </div>

                        @if($registro->updated_at != $registro->created_at)
                            <div class="mb-3">
                                <strong>Última modificación:</strong>
                                <div class="text-muted">
                                    <small>{{ $registro->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card de Ayuda --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-dark">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Consejos de Edición
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning py-2 mb-3">
                            <small>
                                <strong>⚠️ Importante:</strong><br>
                                Solo puedes editar registros pendientes. Una vez resueltos, quedan bloqueados.
                            </small>
                        </div>
                        
                        @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                            <div class="mb-3">
                                <strong class="text-info">📝 Para Residentes:</strong>
                                <small class="d-block text-muted">
                                    Puedes modificar la ubicación, descripción y observaciones de tu reporte.
                                </small>
                            </div>
                        @else
                            <div class="mb-3">
                                <strong class="text-primary">🛡️ Para Personal:</strong>
                                <small class="d-block text-muted">
                                    Puedes cambiar el tipo, prioridad y toda la información del registro.
                                </small>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong class="text-success">💡 Tip:</strong>
                            <small class="d-block text-muted">
                                Asegúrate de proporcionar toda la información relevante para facilitar la resolución.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Guardar Cambios
                            </button>
                            
                            <a href="{{ route('seguridad.show', $registro) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-eye me-2"></i>
                                Ver Registro
                            </a>
                            
                            <a href="{{ route('seguridad.index') }}" class="btn btn-outline-dark">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Listado
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