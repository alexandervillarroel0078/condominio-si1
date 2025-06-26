{{--resources/views/seguridad/show.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('seguridad.index') }}">
                    @if(auth()->user()->can('administrar-seguridad'))
                        Administrar Seguridad
                    @elseif(auth()->user()->can('ver-registros-seguridad'))
                        Registros de Seguridad
                    @elseif(auth()->user()->can('crear-registro-seguridad'))
                        Panel de Seguridad
                    @else
                        Mis Reportes
                    @endif
                </a>
            </li>
            <li class="breadcrumb-item active">
                Registro #{{ $registro->id }}
            </li>
        </ol>
    </nav>

    {{-- Título con Estado --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-2">
                @switch($registro->tipo)
                    @case('ronda')
                        🏃 Ronda de Vigilancia
                        @break
                    @case('incidente')
                        🚨 Incidente de Seguridad
                        @break
                    @case('reporte')
                        📋 Reporte de Turno
                        @break
                @endswitch
                <small class="text-muted">#{{ $registro->id }}</small>
            </h2>
            
            {{-- Estado del Registro --}}
            <div class="mb-2">
                @switch($registro->estado)
                    @case('pendiente')
                        <span class="badge bg-warning text-dark">
                            🔴 PENDIENTE
                        </span>
                        @break
                    @case('en_revision')
                        <span class="badge bg-info">
                            🟡 EN REVISIÓN
                        </span>
                        @break
                    @case('resuelto')
                        <span class="badge bg-success">
                            🟢 RESUELTO
                        </span>
                        @break
                @endswitch
                
                {{-- Prioridad --}}
                @switch($registro->prioridad)
                    @case('alta')
                        <span class="badge bg-danger ms-2">
                            🔴 ALTA PRIORIDAD
                        </span>
                        @break
                    @case('media')
                        <span class="badge bg-warning text-dark ms-2">
                            🟡 PRIORIDAD MEDIA
                        </span>
                        @break
                    @case('baja')
                        <span class="badge bg-success ms-2">
                            🟢 PRIORIDAD BAJA
                        </span>
                        @break
                @endswitch
            </div>
        </div>
        
        {{-- Botones de Acción --}}
        <div class="col-md-4 text-end">
            {{-- Botón Resolver - Solo Personal de Seguridad y Admin --}}
            @if($registro->estado == 'pendiente' && ($registro->tipo == 'incidente' || $registro->origen == 'residente'))
                @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
                    <button type="button" class="btn btn-success btn-lg mb-2" 
                            data-bs-toggle="modal" data-bs-target="#resolverModal">
                        <i class="fas fa-check-circle"></i> Resolver Incidente
                    </button>
                @endif
            @endif

            {{-- Botón Editar - Solo registros pendientes --}}
            @if($registro->estado == 'pendiente')
                {{-- Residentes sus registros --}}
                @if(auth()->user()->can('reportar-incidentes') && $registro->user_id == auth()->id())
                    <a href="{{ route('seguridad.edit', $registro) }}" class="btn btn-warning btn-lg mb-2">
                        <i class="fas fa-edit"></i> Editar Reporte
                    </a>
                @endif
                
                {{-- Personal de Seguridad sus registros --}}
                @if(auth()->user()->can('crear-registro-seguridad') && $registro->user_id == auth()->id())
                    <a href="{{ route('seguridad.edit', $registro) }}" class="btn btn-warning btn-lg mb-2">
                        <i class="fas fa-edit"></i> Editar Registro
                    </a>
                @endif

                {{-- Admin todos los registros --}}
                @if(auth()->user()->can('administrar-seguridad'))
                    <a href="{{ route('seguridad.edit', $registro) }}" class="btn btn-warning btn-lg mb-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endif
            @endif

            {{-- Botón Eliminar - Solo Admin --}}
            @if(auth()->user()->can('administrar-seguridad'))
                <button type="button" class="btn btn-danger btn-lg mb-2" 
                        data-bs-toggle="modal" data-bs-target="#eliminarModal">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            @endif
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Información Principal --}}
    <div class="row">
        {{-- Columna Izquierda - Información del Registro --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información del Registro
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>📅 Fecha y Hora:</strong></p>
                            <p class="text-muted">
                                {{ $registro->fecha_hora->format('d/m/Y') }} a las {{ $registro->fecha_hora->format('H:i') }}
                                @if($registro->fecha_hora->isToday())
                                    <span class="badge bg-success ms-2">Hoy</span>
                                @elseif($registro->fecha_hora->isYesterday())
                                    <span class="badge bg-info ms-2">Ayer</span>
                                @else
                                    <span class="badge bg-secondary ms-2">{{ $registro->fecha_hora->diffForHumans() }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>📍 Ubicación:</strong></p>
                            <p class="text-muted">{{ $registro->ubicacion }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>🏷️ Tipo:</strong></p>
                            <p>
                                @switch($registro->tipo)
                                    @case('ronda')
                                        <span class="badge bg-info">🏃 Ronda</span>
                                        @break
                                    @case('incidente')
                                        <span class="badge bg-warning text-dark">🚨 Incidente</span>
                                        @break
                                    @case('reporte')
                                        <span class="badge bg-success">📋 Reporte</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>🔄 Origen:</strong></p>
                            <p>
                                @if($registro->origen == 'seguridad')
                                    <span class="badge bg-primary">🚪 Personal de Seguridad</span>
                                @else
                                    <span class="badge bg-secondary">🏠 Residente</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <p><strong>📝 Descripción:</strong></p>
                        <div class="bg-light p-3 border rounded">
                            {{ $registro->descripcion }}
                        </div>
                    </div>

                    @if($registro->observaciones)
                        <div class="mt-3">
                            <p><strong>💭 Observaciones:</strong></p>
                            <div class="alert alert-info">
                                {{ $registro->observaciones }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Columna Derecha - Información del Usuario y Resolución --}}
        <div class="col-md-4">
            {{-- Información del Usuario --}}
            @if(auth()->user()->can('administrar-seguridad') || auth()->user()->can('ver-registros-seguridad'))
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-user"></i> Registrado por
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>👤 Usuario:</strong></p>
                        <p class="text-muted">{{ $registro->usuario ? $registro->usuario->name : 'Usuario no disponible' }}</p>
                        
                        <p><strong>📧 Email:</strong></p>
                        <p class="text-muted">{{ $registro->usuario ? $registro->usuario->email : '-' }}</p>
                        
                        <p><strong>⏰ Registrado:</strong></p>
                        <p class="text-muted">{{ $registro->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @endif

            {{-- Información de Resolución --}}
            @if($registro->estado == 'resuelto')
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-check-circle"></i> Información de Resolución
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($registro->resueltoPor)
                            <p><strong>👤 Resuelto por:</strong></p>
                            <p class="text-muted">{{ $registro->resueltoPor->name }}</p>
                        @endif
                        
                        @if($registro->fecha_resolucion)
                            <p><strong>📅 Fecha de Resolución:</strong></p>
                            <p class="text-muted">
                                {{ $registro->fecha_resolucion->format('d/m/Y H:i') }}
                                <br>
                                <small>({{ $registro->fecha_resolucion->diffForHumans() }})</small>
                            </p>
                        @endif
                        
                        @if($registro->observaciones && $registro->estado == 'resuelto')
                            <p><strong>📝 Observaciones de Resolución:</strong></p>
                            <div class="alert alert-success">
                                {{ $registro->observaciones }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Historial de Cambios (Solo para Admin) --}}
            @if(auth()->user()->can('administrar-seguridad'))
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-history"></i> Historial
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>Creado:</strong><br>
                                <small class="text-muted">{{ $registro->created_at->format('d/m/Y H:i') }}</small>
                            </li>
                            
                            @if($registro->updated_at != $registro->created_at)
                                <li class="mb-2">
                                    <strong>Última modificación:</strong><br>
                                    <small class="text-muted">{{ $registro->updated_at->format('d/m/Y H:i') }}</small>
                                </li>
                            @endif
                            
                            @if($registro->fecha_resolucion)
                                <li class="mb-2">
                                    <strong>Resuelto:</strong><br>
                                    <small class="text-muted">{{ $registro->fecha_resolucion->format('d/m/Y H:i') }}</small>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Botones de Navegación --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('seguridad.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
                
                @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
                    <a href="{{ route('seguridad.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Nuevo Registro
                    </a>
                @elseif(auth()->user()->can('reportar-incidentes'))
                    <a href="{{ route('seguridad.reportar-incidente') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-exclamation-triangle"></i> Reportar Otro Incidente
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal para Resolver Incidente --}}
@if($registro->estado == 'pendiente' && ($registro->tipo == 'incidente' || $registro->origen == 'residente'))
    @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
        <div class="modal fade" id="resolverModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('seguridad.resolver', $registro->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-check-circle"></i> Resolver Incidente
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>Registro a resolver:</strong><br>
                                <strong>Tipo:</strong> {{ ucfirst($registro->tipo) }}<br>
                                <strong>Ubicación:</strong> {{ $registro->ubicacion }}<br>
                                <strong>Descripción:</strong> {{ Str::limit($registro->descripcion, 100) }}
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-clipboard-list"></i> 
                                    <strong>Observaciones de Resolución *</strong>
                                </label>
                                <textarea name="observaciones_resolucion" 
                                          class="form-control" 
                                          rows="4"
                                          placeholder="Describe detalladamente cómo se resolvió el incidente, qué acciones se tomaron, etc..."
                                          required></textarea>
                                <small class="form-text text-muted">
                                    Estas observaciones serán visibles para el usuario que reportó el incidente.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Marcar como Resuelto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endif

{{-- Modal para Eliminar - Solo Admin --}}
@if(auth()->user()->can('administrar-seguridad'))
    <div class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>⚠️ ATENCIÓN:</strong> Esta acción no se puede deshacer.
                    </div>
                    
                    <p>¿Estás seguro de que quieres eliminar este registro?</p>
                    
                    <div class="bg-light p-3 rounded">
                        <strong>Registro a eliminar:</strong><br>
                        <strong>ID:</strong> #{{ $registro->id }}<br>
                        <strong>Tipo:</strong> {{ ucfirst($registro->tipo) }}<br>
                        <strong>Ubicación:</strong> {{ $registro->ubicacion }}<br>
                        <strong>Fecha:</strong> {{ $registro->fecha_hora->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form action="{{ route('seguridad.destroy', $registro) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar Definitivamente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection