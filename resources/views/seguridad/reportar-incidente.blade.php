{{-- resources/views/seguridad/reportar-incidente.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('seguridad.index') }}">Mis Reportes</a>
            </li>
            <li class="breadcrumb-item active">
                Reportar Incidente
            </li>
        </ol>
    </nav>

    {{-- Título Principal --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg" 
                         style="width: 80px; height: 80px; font-size: 32px;">
                        🚨
                    </div>
                </div>
                <h1 class="display-5 fw-bold text-danger mb-2">Reportar Incidente</h1>
                <p class="lead text-muted">
                    Reporta cualquier situación que requiera atención inmediata del personal de seguridad
                </p>
            </div>
        </div>
    </div>

    {{-- Alertas de Emergencia --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading mb-1">⚠️ Situaciones de Emergencia</h5>
                        <p class="mb-0">
                            <strong>Si es una emergencia real:</strong> Llama inmediatamente al 911 o al personal de seguridad. 
                            Este formulario es para reportes que no requieren atención inmediata.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas de Sistema --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>¡Reporte enviado!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
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
    <form action="{{ route('seguridad.store') }}" method="POST" id="reporteForm">
        @csrf
        <input type="hidden" name="tipo" value="incidente">
        <input type="hidden" name="prioridad" value="media">
        
        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8 col-md-12">
                {{-- Card Principal del Formulario --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-danger text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-edit me-2 fa-lg"></i>
                            <h4 class="mb-0">Información del Incidente</h4>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            {{-- Tipo de Incidente --}}
                            <div class="col-12">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                    ¿Qué tipo de situación quieres reportar? *
                                </label>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="categoria_incidente" 
                                                   id="categoria_seguridad" value="seguridad" required>
                                            <label class="form-check-label" for="categoria_seguridad">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="badge bg-danger fs-5">🔒</span>
                                                    </div>
                                                    <div>
                                                        <strong>Seguridad</strong>
                                                        <small class="d-block text-muted">Personas sospechosas, ruidos extraños, etc.</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="categoria_incidente" 
                                                   id="categoria_mantenimiento" value="mantenimiento" required>
                                            <label class="form-check-label" for="categoria_mantenimiento">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="badge bg-warning fs-5">🔧</span>
                                                    </div>
                                                    <div>
                                                        <strong>Mantenimiento</strong>
                                                        <small class="d-block text-muted">Daños en instalaciones, averías, etc.</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="categoria_incidente" 
                                                   id="categoria_ruido" value="ruido" required>
                                            <label class="form-check-label" for="categoria_ruido">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="badge bg-info fs-5">🔊</span>
                                                    </div>
                                                    <div>
                                                        <strong>Ruido</strong>
                                                        <small class="d-block text-muted">Ruidos molestos, música alta, etc.</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="categoria_incidente" 
                                                   id="categoria_otros" value="otros" required>
                                            <label class="form-check-label" for="categoria_otros">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <span class="badge bg-secondary fs-5">📋</span>
                                                    </div>
                                                    <div>
                                                        <strong>Otros</strong>
                                                        <small class="d-block text-muted">Cualquier otra situación</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ubicación --}}
                            <div class="col-12">
                                <label for="ubicacion" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    ¿Dónde está ocurriendo? *
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-map-marker-alt text-danger"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('ubicacion') is-invalid @enderror" 
                                           id="ubicacion"
                                           name="ubicacion" 
                                           placeholder="Ej: Lobby principal, Piscina, Parqueadero B, mi apartamento..."
                                           value="{{ old('ubicacion') }}"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="miUbicacionBtn">
                                        <i class="fas fa-home me-1"></i>
                                        Mi Apto
                                    </button>
                                    @error('ubicacion')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Sé lo más específico posible para que el personal pueda ubicar rápidamente el lugar
                                </small>
                            </div>

                            {{-- Descripción --}}
                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-bold">
                                    <i class="fas fa-align-left text-primary me-2"></i>
                                    ¿Qué está pasando exactamente? *
                                </label>
                                <textarea class="form-control form-control-lg @error('descripcion') is-invalid @enderror" 
                                          id="descripcion"
                                          name="descripcion" 
                                          rows="5" 
                                          placeholder="Describe detalladamente la situación:&#10;• ¿Qué viste o escuchaste?&#10;• ¿Cuándo empezó?&#10;• ¿Hay personas involucradas?&#10;• ¿Es algo que está ocurriendo ahora?"
                                          required>{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Mientras más detalles proporciones, mejor podrá atenderse tu reporte
                                </small>
                            </div>

                            {{-- Urgencia --}}
                            <div class="col-12">
                                <label class="form-label fw-bold mb-3">
                                    <i class="fas fa-tachometer-alt text-warning me-2"></i>
                                    ¿Qué tan urgente es la situación? *
                                </label>
                                
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="urgencia" id="urgencia_baja" value="baja" required>
                                    <label class="btn btn-outline-success" for="urgencia_baja">
                                        <i class="fas fa-clock me-1"></i>
                                        No Urgente
                                        <small class="d-block">Puede esperar</small>
                                    </label>

                                    <input type="radio" class="btn-check" name="urgencia" id="urgencia_media" value="media" required checked>
                                    <label class="btn btn-outline-warning" for="urgencia_media">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Moderada
                                        <small class="d-block">Atención hoy</small>
                                    </label>

                                    <input type="radio" class="btn-check" name="urgencia" id="urgencia_alta" value="alta" required>
                                    <label class="btn btn-outline-danger" for="urgencia_alta">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Urgente
                                        <small class="d-block">Inmediata</small>
                                    </label>
                                </div>
                            </div>

                            {{-- Información Adicional --}}
                            <div class="col-12">
                                <label for="observaciones" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note text-info me-2"></i>
                                    Información adicional
                                    <span class="badge bg-secondary ms-2">Opcional</span>
                                </label>
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          id="observaciones"
                                          name="observaciones" 
                                          rows="3" 
                                          placeholder="¿Hay algo más que debamos saber?&#10;• ¿Hay testigos?&#10;• ¿Has notado esto antes?&#10;• ¿Tienes fotos o videos?">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Cualquier detalle adicional que pueda ayudar
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna Lateral --}}
            <div class="col-lg-4 col-md-12">
                {{-- Card de Información --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Tu Información
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Reportado por:</strong>
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
                            <strong>Tipo de reporte:</strong>
                            <div>
                                <span class="badge bg-warning text-dark">🚨 Incidente</span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Tu reporte será enviado inmediatamente al personal de seguridad
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Card de Consejos --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            Consejos para un Buen Reporte
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="me-2">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                                <div>
                                    <strong>Sé específico</strong>
                                    <small class="d-block text-muted">
                                        Describe exactamente lo que viste o escuchaste
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="me-2">
                                    <i class="fas fa-map-marker-alt text-success"></i>
                                </div>
                                <div>
                                    <strong>Ubicación precisa</strong>
                                    <small class="d-block text-muted">
                                        Menciona referencias claras del lugar
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="me-2">
                                    <i class="fas fa-clock text-success"></i>
                                </div>
                                <div>
                                    <strong>Momento exacto</strong>
                                    <small class="d-block text-muted">
                                        Indica si está pasando ahora o cuándo ocurrió
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-0">
                            <div class="d-flex">
                                <div class="me-2">
                                    <i class="fas fa-users text-success"></i>
                                </div>
                                <div>
                                    <strong>Personas involucradas</strong>
                                    <small class="d-block text-muted">
                                        Describe a las personas si es relevante
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-3 mb-3">
                            <button type="submit" class="btn btn-danger btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Enviar Reporte
                            </button>
                            
                            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="window.history.back()">
                                <i class="fas fa-arrow-left me-2"></i>
                                Cancelar
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Los campos marcados con * son obligatorios
                            </small>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="text-center">
                            <a href="{{ route('seguridad.index') }}" class="btn btn-link">
                                <i class="fas fa-list me-1"></i>
                                Ver mis reportes anteriores
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Botón "Mi Apartamento"
    document.getElementById('miUbicacionBtn').addEventListener('click', function() {
        const ubicacionInput = document.getElementById('ubicacion');
        const userName = '{{ auth()->user()->name }}';
        ubicacionInput.value = `Apartamento de ${userName}`;
        ubicacionInput.focus();
    });
    
    // Validación del formulario
    const form = document.getElementById('reporteForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        // Validar que se haya seleccionado una categoría
        const categoriaSeleccionada = document.querySelector('input[name="categoria_incidente"]:checked');
        if (!categoriaSeleccionada) {
            e.preventDefault();
            alert('Por favor selecciona el tipo de incidente');
            return;
        }
        
        // Validar que se haya seleccionado urgencia
        const urgenciaSeleccionada = document.querySelector('input[name="urgencia"]:checked');
        if (!urgenciaSeleccionada) {
            e.preventDefault();
            alert('Por favor indica el nivel de urgencia');
            return;
        }
        
        // Deshabilitar botón y mostrar loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando Reporte...';
        
        // Re-habilitar después de 10 segundos por si hay error
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Enviar Reporte';
        }, 10000);
    });
});
</script>
@endsection