@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">{{ $titulo }}</h2>

    {{-- Botones de acción según permisos --}}
    <div class="mb-3">
        {{-- Crear Registro - Seguridad y Admins --}}
        @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
            <a href="{{ route('seguridad.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Registro
            </a>
        @endif

        {{-- Reportar Incidente Rápido - Solo Residentes --}}
        @if(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
            <a href="{{ route('seguridad.reportar-incidente') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i> Reportar Incidente
            </a>
        @endif

        {{-- Dashboard de Seguridad - Solo Personal de Seguridad --}}
        @if(auth()->user()->can('crear-registro-seguridad') && !auth()->user()->can('administrar-seguridad'))
            <a href="{{ route('seguridad.index', ['estado' => 'pendiente']) }}" class="btn btn-danger">
                <i class="fas fa-bell"></i> Pendientes
            </a>
            <a href="{{ route('seguridad.index', ['tipo' => 'incidente', 'origen' => 'residente']) }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Reportes Residentes
            </a>
        @endif
    </div>

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

    {{-- Buscador y Filtros --}}
    <form method="GET" action="{{ route('seguridad.index') }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Buscar por ubicación, descripción..."
                           value="{{ request('search') }}"
                           aria-label="Buscar registros"
                           title="Busca por ubicación, descripción o cualquier texto del registro">
                    @if(request('search'))
                        <button class="btn btn-outline-secondary" type="button" 
                                onclick="this.form.search.value=''; this.form.submit();"
                                title="Limpiar búsqueda">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
                @if(request('search'))
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Mostrando resultados para: "<strong>{{ request('search') }}</strong>"
                    </small>
                @endif
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select" 
                        aria-label="Filtrar por tipo"
                        title="Selecciona el tipo de registro a mostrar">
                    <option value="">📋 Todos los tipos</option>
                    <option value="ronda" {{ request('tipo') == 'ronda' ? 'selected' : '' }}>🏃 Ronda</option>
                    <option value="incidente" {{ request('tipo') == 'incidente' ? 'selected' : '' }}>🚨 Incidente</option>
                    <option value="reporte" {{ request('tipo') == 'reporte' ? 'selected' : '' }}>📋 Reporte</option>
                </select>
                @if(request('tipo'))
                    <small class="form-text text-success">
                        <i class="fas fa-filter"></i> Tipo: {{ ucfirst(request('tipo')) }}
                    </small>
                @endif
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select"
                        aria-label="Filtrar por estado"
                        title="Selecciona el estado de los registros">
                    <option value="">🔘 Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>🔴 Pendiente</option>
                    <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>🟡 En Revisión</option>
                    <option value="resuelto" {{ request('estado') == 'resuelto' ? 'selected' : '' }}>🟢 Resuelto</option>
                </select>
                @if(request('estado'))
                    <small class="form-text text-info">
                        <i class="fas fa-flag"></i> Estado: {{ ucfirst(str_replace('_', ' ', request('estado'))) }}
                    </small>
                @endif
            </div>
            <div class="col-md-2">
                <select name="prioridad" class="form-select"
                        aria-label="Filtrar por prioridad"
                        title="Selecciona la prioridad de los registros">
                    <option value="">⚡ Todas las prioridades</option>
                    <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>🔴 Alta</option>
                    <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>🟡 Media</option>
                    <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>🟢 Baja</option>
                </select>
                @if(request('prioridad'))
                    <small class="form-text text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Prioridad: {{ ucfirst(request('prioridad')) }}
                    </small>
                @endif
            </div>
            <div class="col-md-2">
                <div class="d-grid gap-1">
                    <button class="btn btn-primary" type="submit"
                            title="Aplicar filtros seleccionados">
                        <i class="fas fa-search"></i> 
                        @if(request()->anyFilled(['search', 'tipo', 'estado', 'prioridad']))
                            Filtrar
                        @else
                            Buscar
                        @endif
                    </button>
                    @if(request()->anyFilled(['search', 'tipo', 'estado', 'prioridad']))
                        <a href="{{ route('seguridad.index') }}" 
                           class="btn btn-outline-secondary"
                           title="Quitar todos los filtros aplicados">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Resumen de filtros activos --}}
        @if(request()->anyFilled(['search', 'tipo', 'estado', 'prioridad']))
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-filter"></i> 
                    Filtros activos: 
                    @if(request('search'))
                        <span class="badge bg-light text-dark me-1">Búsqueda: "{{ request('search') }}"</span>
                    @endif
                    @if(request('tipo'))
                        <span class="badge bg-primary me-1">{{ ucfirst(request('tipo')) }}</span>
                    @endif
                    @if(request('estado'))
                        <span class="badge bg-info me-1">{{ ucfirst(str_replace('_', ' ', request('estado'))) }}</span>
                    @endif
                    @if(request('prioridad'))
                        <span class="badge bg-warning text-dark me-1">Prioridad {{ ucfirst(request('prioridad')) }}</span>
                    @endif
                    — {{ $registros->total() }} resultado(s)
                </small>
            </div>
        @endif
    </form>

    {{-- Estadísticas Rápidas --}}
    @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>{{ $registros->where('tipo', 'ronda')->count() }}</h5>
                        <small>🏃 Rondas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5>{{ $registros->where('tipo', 'incidente')->count() }}</h5>
                        <small>🚨 Incidentes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>{{ $registros->where('tipo', 'reporte')->count() }}</h5>
                        <small>📋 Reportes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>{{ $registros->where('estado', 'pendiente')->count() }}</h5>
                        <small>⚠️ Pendientes</small>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tabla de registros --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Fecha/Hora</th>
                    <th>Tipo</th>
                    <th>Origen</th>
                    {{-- Mostrar columna Usuario solo para admin/directiva --}}
                    @if(auth()->user()->can('administrar-seguridad') || auth()->user()->can('ver-registros-seguridad'))
                        <th>Usuario</th>
                    @endif
                    <th>Ubicación</th>
                    <th>Descripción</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($registros as $registro)
                @php
                    $rowClass = '';
                    if ($registro->estado == 'pendiente' && $registro->prioridad == 'alta') {
                        $rowClass = 'table-danger';
                    } elseif ($registro->estado == 'pendiente' && $registro->prioridad == 'media') {
                        $rowClass = 'table-warning';
                    } elseif ($registro->origen == 'residente' && $registro->estado == 'pendiente') {
                        $rowClass = 'table-info';
                    }
                @endphp

                <tr class="{{ $rowClass }}">
                    <td>{{ $registro->id }}</td>
                    <td>
                        <small>{{ $registro->fecha_hora->format('d/m/Y') }}</small><br>
                        <strong>{{ $registro->fecha_hora->format('H:i') }}</strong>
                        @if($registro->fecha_hora->isToday())
                            <br><small class="text-success"><i class="fas fa-clock"></i> Hoy</small>
                        @elseif($registro->fecha_hora->isYesterday())
                            <br><small class="text-info"><i class="fas fa-clock"></i> Ayer</small>
                        @endif
                    </td>
                    <td>
                        @switch($registro->tipo)
                            @case('ronda')
                                <span class="badge bg-info">
                                    🏃 Ronda
                                </span>
                                @break
                            @case('incidente')
                                <span class="badge bg-warning text-dark">
                                    🚨 Incidente
                                </span>
                                @break
                            @case('reporte')
                                <span class="badge bg-success">
                                    📋 Reporte
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        @if($registro->origen == 'seguridad')
                            <span class="badge bg-primary">
                                🚪 Seguridad
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                🏠 Residente
                            </span>
                            @if($registro->estado == 'pendiente')
                                <br><small class="text-danger"><i class="fas fa-bell"></i> Nuevo</small>
                            @endif
                        @endif
                    </td>
                    
                    {{-- Mostrar usuario solo para admin/directiva --}}
                    @if(auth()->user()->can('administrar-seguridad') || auth()->user()->can('ver-registros-seguridad'))
                        <td>
                            <small>{{ $registro->usuario ? $registro->usuario->name : '-' }}</small>
                        </td>
                    @endif
                    
                    <td>
                        <strong>{{ Str::limit($registro->ubicacion, 20) }}</strong>
                    </td>
                    <td>{{ Str::limit($registro->descripcion, 40) }}</td>
                    <td>
                        @switch($registro->prioridad)
                            @case('alta')
                                <span class="badge bg-danger">
                                    🔴 Alta
                                </span>
                                @break
                            @case('media')
                                <span class="badge bg-warning text-dark">
                                    🟡 Media
                                </span>
                                @break
                            @case('baja')
                                <span class="badge bg-success">
                                    🟢 Baja
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        @switch($registro->estado)
                            @case('pendiente')
                                <span class="badge bg-warning text-dark">
                                    🔴 Pendiente
                                </span>
                                @break
                            @case('en_revision')
                                <span class="badge bg-info">
                                    🟡 En Revisión
                                </span>
                                @break
                            @case('resuelto')
                                <span class="badge bg-success">
                                    🟢 Resuelto
                                </span>
                                @if($registro->resueltoPor)
                                    <br><small class="text-muted">Por: {{ $registro->resueltoPor->name }}</small>
                                @endif
                                @break
                        @endswitch
                    </td>
                    <td>
                        <div class="btn-group-vertical btn-group-sm" role="group">
                            {{-- Ver detalles - Todos pueden --}}
                            <a href="{{ route('seguridad.show', $registro->id) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            
                            {{-- Marcar En Revisión - Solo Personal de Seguridad y Admin --}}
                            @if($registro->estado == 'pendiente')
                                @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
                                    <button type="button" class="btn btn-outline-info btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#revisionModal{{ $registro->id }}">
                                        <i class="fas fa-search"></i> En Revisión
                                    </button>
                                @endif
                            @endif

                            {{-- Resolver - Solo Personal de Seguridad y Admin --}}
                            @if(($registro->estado == 'pendiente' || $registro->estado == 'en_revision') && ($registro->tipo == 'incidente' || $registro->origen == 'residente'))
                                @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
                                    <button type="button" class="btn btn-outline-success btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#resolverModal{{ $registro->id }}">
                                        <i class="fas fa-check"></i> Resolver
                                    </button>
                                @endif
                            @endif

                            {{-- Editar - Solo registros pendientes y evitar duplicados por múltiples permisos --}}
                            @if($registro->estado == 'pendiente')
                                @php
                                    $puedeEditar = false;
                                    
                                    // Admin puede editar todo
                                    if(auth()->user()->can('administrar-seguridad')) {
                                        $puedeEditar = true;
                                    }
                                    // Personal de seguridad y residentes solo sus registros (si no son admin)
                                    elseif(
                                        (auth()->user()->can('crear-registro-seguridad') && $registro->user_id == auth()->id()) ||
                                        (auth()->user()->can('reportar-incidentes') && $registro->user_id == auth()->id())
                                    ) {
                                        $puedeEditar = true;
                                    }
                                @endphp
                                
                                @if($puedeEditar)
                                    <a href="{{ route('seguridad.edit', $registro) }}"
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @endif
                            @endif

                            {{-- Eliminar - Solo Admin --}}
                            @if(auth()->user()->can('administrar-seguridad'))
                                <form action="{{ route('seguridad.destroy', $registro) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('¿Eliminar este registro?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Modal para marcar en revisión --}}
                @if($registro->estado == 'pendiente')
                    @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
                        <div class="modal fade" id="revisionModal{{ $registro->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('seguridad.marcar-revision', $registro->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-search"></i> Marcar En Revisión
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Registro:</strong> #{{ $registro->id }}<br>
                                                <strong>Tipo:</strong> {{ $registro->tipo_formateado }}<br>
                                                <strong>Ubicación:</strong> {{ $registro->ubicacion }}<br>
                                                <strong>Descripción:</strong> {{ Str::limit($registro->descripcion, 100) }}
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Observaciones de Revisión</label>
                                                <textarea name="observaciones_revision" class="form-control" rows="3"
                                                          placeholder="Indica qué acciones estás tomando para revisar este caso..."></textarea>
                                                <small class="form-text text-muted">
                                                    Esto indica que estás trabajando en la resolución del caso.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-info">
                                                <i class="fas fa-search"></i> Marcar En Revisión
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Modal para resolver incidente --}}
                @if(($registro->estado == 'pendiente' || $registro->estado == 'en_revision') && ($registro->tipo == 'incidente' || $registro->origen == 'residente'))
                    @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
                        <div class="modal fade" id="resolverModal{{ $registro->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('seguridad.resolver', $registro->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-check-circle"></i> Resolver Registro
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Registro:</strong> #{{ $registro->id }}<br>
                                                <strong>Estado actual:</strong> 
                                                @if($registro->estado == 'pendiente')
                                                    <span class="badge bg-warning text-dark">🔴 Pendiente</span>
                                                @else
                                                    <span class="badge bg-info">🟡 En Revisión</span>
                                                @endif
                                                <br>
                                                <strong>Tipo:</strong> {{ $registro->tipo_formateado }}<br>
                                                <strong>Ubicación:</strong> {{ $registro->ubicacion }}
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Observaciones de Resolución *</label>
                                                <textarea name="observaciones_resolucion" class="form-control" rows="3"
                                                          placeholder="Describe cómo se resolvió el incidente..."
                                                          required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Marcar como Resuelto
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

            @empty
                <tr>
                    @php
                        $colspan = (auth()->user()->can('administrar-seguridad') || auth()->user()->can('ver-registros-seguridad')) ? 10 : 9;
                    @endphp
                    <td colspan="{{ $colspan }}" class="text-center py-4">
                        {{-- Mensaje cuando no hay registros --}}
                        <div class="text-muted">
                            <i class="fas fa-shield-alt fa-3x mb-3"></i>
                            <p class="mb-0">
                                @if(request()->anyFilled(['search', 'tipo', 'estado', 'prioridad']))
                                    No se encontraron registros con los filtros aplicados.
                                @elseif(auth()->user()->can('reportar-incidentes') && !auth()->user()->can('crear-registro-seguridad'))
                                    No tienes reportes registrados.
                                @else
                                    No hay registros de seguridad.
                                @endif
                            </p>
                            
                            @if(request()->anyFilled(['search', 'tipo', 'estado', 'prioridad']))
                                <a href="{{ route('seguridad.index') }}" class="btn btn-outline-primary mt-2">
                                    <i class="fas fa-times"></i> Quitar Filtros
                                </a>
                            @else
                                @if(auth()->user()->can('crear-registro-seguridad') || auth()->user()->can('administrar-seguridad'))
                                    <a href="{{ route('seguridad.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Crear Primer Registro
                                    </a>
                                @elseif(auth()->user()->can('reportar-incidentes'))
                                    <a href="{{ route('seguridad.reportar-incidente') }}" class="btn btn-warning mt-2">
                                        <i class="fas fa-exclamation-triangle"></i> Reportar Primer Incidente
                                    </a>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center">
        {{ $registros->appends([
            'search' => request('search'),
            'tipo' => request('tipo'),
            'estado' => request('estado'),
            'prioridad' => request('prioridad')
        ])->links() }}
    </div>
</div>
@endsection