{{-- resources/views/seguridad/mis-reportes.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container-fluid py-4">
    {{-- Header con Título y Estadísticas --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="me-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; font-size: 24px;">
                            📋
                        </div>
                    </div>
                    <div>
                        <h2 class="mb-1">Mis Reportes</h2>
                        <p class="text-muted mb-0">
                            Historial de incidentes reportados por ti
                        </p>
                    </div>
                </div>
                
                {{-- Botón Reportar Nuevo --}}
                <div>
                    <a href="{{ route('seguridad.create') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>
                        Reportar Incidente
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas Rápidas --}}
    @if($reportes->count() > 0)
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="card-title mb-1">
                            {{ $reportes->where('estado', 'pendiente')->count() }}
                        </h5>
                        <p class="card-text mb-0">Pendientes</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h5 class="card-title mb-1">
                            {{ $reportes->where('estado', 'en_revision')->count() }}
                        </h5>
                        <p class="card-text mb-0">En Revisión</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="card-title mb-1">
                            {{ $reportes->where('estado', 'resuelto')->count() }}
                        </h5>
                        <p class="card-text mb-0">Resueltos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <h5 class="card-title mb-1">
                            {{ $reportes->total() }}
                        </h5>
                        <p class="card-text mb-0">Total</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

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

    {{-- Filtros y Búsqueda --}}
    @if($reportes->count() > 0)
        <form method="GET" action="{{ route('seguridad.mis-reportes') }}" class="mb-3">
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
                               aria-label="Buscar reportes">
                        @if(request('search'))
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="this.form.search.value=''; this.form.submit();">
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
                
                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">🔘 Todos los estados</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                            🔴 Pendiente
                        </option>
                        <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>
                            🟡 En Revisión
                        </option>
                        <option value="resuelto" {{ request('estado') == 'resuelto' ? 'selected' : '' }}>
                            🟢 Resuelto
                        </option>
                    </select>
                    @if(request('estado'))
                        <small class="form-text text-info">
                            <i class="fas fa-flag"></i> Estado: {{ ucfirst(str_replace('_', ' ', request('estado'))) }}
                        </small>
                    @endif
                </div>
                
                <div class="col-md-3">
                    <select name="fecha" class="form-select">
                        <option value="">📅 Todas las fechas</option>
                        <option value="hoy" {{ request('fecha') == 'hoy' ? 'selected' : '' }}>
                            Hoy
                        </option>
                        <option value="semana" {{ request('fecha') == 'semana' ? 'selected' : '' }}>
                            Última semana
                        </option>
                        <option value="mes" {{ request('fecha') == 'mes' ? 'selected' : '' }}>
                            Último mes
                        </option>
                    </select>
                    @if(request('fecha'))
                        <small class="form-text text-success">
                            <i class="fas fa-calendar"></i> Período: {{ ucfirst(request('fecha')) }}
                        </small>
                    @endif
                </div>
                
                <div class="col-md-2">
                    <div class="d-grid gap-1">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> 
                            @if(request()->anyFilled(['search', 'estado', 'fecha']))
                                Filtrar
                            @else
                                Buscar
                            @endif
                        </button>
                        @if(request()->anyFilled(['search', 'estado', 'fecha']))
                            <a href="{{ route('seguridad.mis-reportes') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Resumen de filtros activos --}}
            @if(request()->anyFilled(['search', 'estado', 'fecha']))
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="fas fa-filter"></i> 
                        Filtros activos: 
                        @if(request('search'))
                            <span class="badge bg-light text-dark me-1">Búsqueda: "{{ request('search') }}"</span>
                        @endif
                        @if(request('estado'))
                            <span class="badge bg-info me-1">{{ ucfirst(str_replace('_', ' ', request('estado'))) }}</span>
                        @endif
                        @if(request('fecha'))
                            <span class="badge bg-success me-1">{{ ucfirst(request('fecha')) }}</span>
                        @endif
                        — {{ $reportes->total() }} resultado(s)
                    </small>
                </div>
            @endif
        </form>
    @endif

    {{-- Tabla de Reportes --}}
    @if($reportes->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha/Hora</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Resolución</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportes as $reporte)
                        @php
                            $rowClass = '';
                            if ($reporte->estado == 'pendiente') {
                                $rowClass = 'table-warning';
                            } elseif ($reporte->estado == 'en_revision') {
                                $rowClass = 'table-info';
                            } elseif ($reporte->estado == 'resuelto') {
                                $rowClass = 'table-success';
                            }
                        @endphp

                        <tr class="{{ $rowClass }}">
                            <td>
                                <strong>#{{ $reporte->id }}</strong>
                                @if($reporte->created_at->isToday())
                                    <br><small class="text-success"><i class="fas fa-clock"></i> Hoy</small>
                                @elseif($reporte->created_at->isYesterday())
                                    <br><small class="text-info"><i class="fas fa-clock"></i> Ayer</small>
                                @endif
                            </td>
                            <td>
                                <small>{{ $reporte->fecha_hora->format('d/m/Y') }}</small><br>
                                <strong>{{ $reporte->fecha_hora->format('H:i') }}</strong>
                                <br><small class="text-muted">{{ $reporte->fecha_hora->diffForHumans() }}</small>
                            </td>
                            <td>
                                @switch($reporte->estado)
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
                                        @if($reporte->resueltoPor)
                                            <br><small class="text-muted">Por: {{ $reporte->resueltoPor->name }}</small>
                                        @endif
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <strong>{{ Str::limit($reporte->ubicacion, 25) }}</strong>
                                <br><small class="text-muted">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    {{ $reporte->ubicacion }}
                                </small>
                            </td>
                            <td>{{ Str::limit($reporte->descripcion, 50) }}</td>
                            <td>
                                @switch($reporte->prioridad)
                                    @case('alta')
                                        <span class="badge bg-danger">🔴 Alta</span>
                                        @break
                                    @case('media')
                                        <span class="badge bg-warning text-dark">🟡 Media</span>
                                        @break
                                    @case('baja')
                                        <span class="badge bg-success">🟢 Baja</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if($reporte->estado == 'resuelto' && $reporte->observaciones)
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i>
                                        {{ Str::limit($reporte->observaciones, 30) }}
                                    </small>
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group-vertical btn-group-sm" role="group">
                                    <a href="{{ route('seguridad.show', $reporte) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    
                                    @if($reporte->estado == 'pendiente')
                                        <a href="{{ route('seguridad.edit', $reporte) }}" 
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($reportes->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $reportes->appends([
                    'search' => request('search'),
                    'estado' => request('estado'),
                    'fecha' => request('fecha')
                ])->links() }}
            </div>
        @endif
    @else
        {{-- Estado Vacío --}}
        <div class="text-center py-5">
            <div class="mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 100px; height: 100px; font-size: 40px;">
                    📝
                </div>
            </div>
            
            <h4 class="text-muted mb-3">
                @if(request()->anyFilled(['search', 'estado', 'fecha']))
                    No se encontraron reportes con los filtros aplicados
                @else
                    No tienes reportes aún
                @endif
            </h4>
            <p class="text-muted mb-4">
                @if(request()->anyFilled(['search', 'estado', 'fecha']))
                    Intenta ajustar los filtros para encontrar lo que buscas.
                @else
                    Cuando reportes un incidente, aparecerá aquí para que puedas hacer seguimiento.
                @endif
            </p>
            
            @if(request()->anyFilled(['search', 'estado', 'fecha']))
                <a href="{{ route('seguridad.mis-reportes') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-times"></i> Quitar Filtros
                </a>
            @endif
            
            <a href="{{ route('seguridad.create') }}" class="btn btn-warning btn-lg">
                <i class="fas fa-plus-circle me-2"></i>
                @if(request()->anyFilled(['search', 'estado', 'fecha']))
                    Reportar Incidente
                @else
                    Reportar tu Primer Incidente
                @endif
            </a>
        </div>
    @endif

    {{-- Botón Flotante para Móviles --}}
    <div class="d-md-none">
        <a href="{{ route('seguridad.create') }}" 
           class="btn btn-warning rounded-circle shadow-lg"
           style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; z-index: 1000;">
            <i class="fas fa-plus fa-lg"></i>
        </a>
    </div>
</div>
@endsection