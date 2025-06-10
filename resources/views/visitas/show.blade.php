{{-- resources/views/visitas/show.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Detalle de la Visita</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-0">Información de la Visita</h5>
                </div>
                <div class="col-md-4 text-end">
                    @switch($visita->estado)
                        @case('pendiente')
                            <span class="badge bg-warning fs-6">Pendiente</span>
                            @break
                        @case('en_curso')
                            <span class="badge bg-info fs-6">En Curso</span>
                            @break
                        @case('finalizada')
                            <span class="badge bg-success fs-6">Finalizada</span>
                            @break
                        @case('rechazada')
                            <span class="badge bg-danger fs-6">Rechazada</span>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Código de Visita:</strong> <span class="text-primary fs-4">{{ $visita->codigo }}</span></p>
                    <p><strong>Visitante:</strong> {{ $visita->nombre_visitante }}</p>
                    <p><strong>CI del Visitante:</strong> {{ $visita->ci_visitante }}</p>
                    <p><strong>Residente:</strong>
                        {{ $visita->residente
                            ? $visita->residente->nombre_completo
                            : 'Sin asignar' }}
                    </p>
                    <p><strong>Motivo:</strong> {{ $visita->motivo }}</p>
                    <p><strong>Placa del Vehículo:</strong> {{ $visita->placa_vehiculo ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha de Inicio:</strong> {{ $visita->fecha_inicio->format('d/m/Y H:i') }}</p>
                    <p><strong>Fecha de Fin:</strong> {{ $visita->fecha_fin->format('d/m/Y H:i') }}</p>
                    <p><strong>Estado:</strong> {{ ucfirst(str_replace('_', ' ', $visita->estado)) }}</p>
                    
                    @if($visita->hora_entrada)
                        <p><strong>Hora de Entrada:</strong> {{ $visita->hora_entrada->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Registrado por:</strong> 
                            {{ $visita->userEntrada ? $visita->userEntrada->name : 'Sistema' }}
                        </p>
                    @endif

                    @if($visita->hora_salida)
                        <p><strong>Hora de Salida:</strong> {{ $visita->hora_salida->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Registrado por:</strong> 
                            {{ $visita->userSalida ? $visita->userSalida->name : 'Sistema' }}
                        </p>
                    @endif

                    @if($visita->observaciones)
                        <p><strong>Observaciones:</strong> {{ $visita->observaciones }}</p>
                    @endif

                    <p><strong>Creado:</strong> {{ $visita->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones según el estado --}}
    <div class="mt-3">
        @if($visita->estado == 'pendiente')
            {{-- @can('registrar entrada') --}}
            <form action="{{ route('visitas.entrada', $visita) }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-success"
                        onclick="return confirm('¿Registrar entrada de {{ $visita->nombre_visitante }}?')">
                    Registrar Entrada
                </button>
            </form>
            {{-- @endcan --}}

            {{-- @can('editar visitas') --}}
            <a href="{{ route('visitas.edit', $visita) }}" class="btn btn-warning">Editar Visita</a>
            {{-- @endcan --}}
        @endif

        @if($visita->estado == 'en_curso')
            {{-- @can('registrar salida') --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#salidaModal">
                Registrar Salida
            </button>
            {{-- @endcan --}}
        @endif

        <a href="{{ route('visitas.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    {{-- Modal para registrar salida con observaciones --}}
    @if($visita->estado == 'en_curso')
    <div class="modal fade" id="salidaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Salida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('visitas.salida', $visita) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p><strong>Visitante:</strong> {{ $visita->nombre_visitante }}</p>
                        <p><strong>CI:</strong> {{ $visita->ci_visitante }}</p>
                        
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                            <textarea name="observaciones" 
                                      id="observaciones" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Ej: Salida normal, sin inconvenientes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Salida</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection