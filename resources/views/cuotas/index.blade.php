@extends('layouts.ap')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filtro = document.getElementById('filtro_tiempo');

        const fechaDesde = document.getElementById('fechaDesdeContainer');
        const fechaHasta = document.getElementById('fechaHastaContainer');
        const mes = document.getElementById('mesContainer');
        const semana = document.getElementById('semanaContainer');
        const anio = document.getElementById('anioContainer');

        function actualizarCampos() {
            fechaDesde.style.display = 'none';
            fechaHasta.style.display = 'none';
            mes.style.display = 'none';
            semana.style.display = 'none';
            anio.style.display = 'none';

            const tipo = filtro.value;

            if (tipo === 'fecha') {
                fechaDesde.style.display = 'block';
                fechaHasta.style.display = 'block';
            } else if (tipo === 'mes') {
                mes.style.display = 'block';
            } else if (tipo === 'semana') {
                semana.style.display = 'block';
            } else if (tipo === 'anio') {
                anio.style.display = 'block';
            }
        }

        filtro.addEventListener('change', actualizarCampos);
        actualizarCampos(); // por si viene con valor
    });
</script>


@section('content')
<div class="container">
    <h2 class="mb-4">Cuotas y Pagos</h2>

    <a href="{{ route('cuotas.create') }}" class="btn btn-primary mb-3">Emitir Nueva Cuota</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- filtros --}}
    <div class="container mb-4">
        <div class="card shadow">
            <div class="card-body">
                <form method="GET" action="{{ route('cuotas.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Buscar por residente o unidad</label>
                            <input type="text" name="search" class="form-control" placeholder="Ej: Juan Pérez, Unidad 101" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pend</option>
                                <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa</option>
                                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancel</option>
                                <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            </select>
                        </div>



                        <div class="col-md-2">
                            <label class="form-label">Tipo de filtro</label>
                            <select name="filtro_tiempo" id="filtro_tiempo" class="form-select">
                                <option value="">-- Tipo --</option>
                                <option value="fecha" {{ request('filtro_tiempo') == 'fecha' ? 'selected' : '' }}>Por Fecha</option>
                                <option value="mes" {{ request('filtro_tiempo') == 'mes' ? 'selected' : '' }}>Por Mes</option>
                                <option value="semana" {{ request('filtro_tiempo') == 'semana' ? 'selected' : '' }}>Por Semana</option>
                                <option value="anio" {{ request('filtro_tiempo') == 'anio' ? 'selected' : '' }}>Por Año</option>
                            </select>
                        </div>

                        {{-- Por FECHA --}}
                        <div class="col-md-2" id="fechaDesdeContainer" style="display: none;">
                            <label class="form-label">Desde</label>
                            <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="col-md-2" id="fechaHastaContainer" style="display: none;">
                            <label class="form-label">Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                        </div>

                        {{-- Por MES --}}
                        <div class="col-md-2" id="mesContainer" style="display: none;">
                            <label class="form-label">Mes</label>
                            <input type="month" name="mes" class="form-control" value="{{ request('mes') }}">
                        </div>

                        {{-- Por SEMANA --}}
                        <div class="col-md-2" id="semanaContainer" style="display: none;">
                            <label class="form-label">Semana</label>
                            <input type="week" name="semana" class="form-control" value="{{ request('semana') }}">
                        </div>

                        {{-- Por AÑO --}}
                        <div class="col-md-2" id="anioContainer" style="display: none;">
                            <label class="form-label">Año</label>
                            <input type="number" name="anio" class="form-control" min="2000" max="{{ date('Y') + 1 }}" value="{{ request('anio') }}">
                        </div>

                        <div class="col-md-1 d-grid">
                            <button class="btn btn-outline-primary" type="submit">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Unidad</th>
                <th>Residente</th>
                <th>Mes</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cuotas as $cuota)
            <tr>
                <td>{{ $cuota->id }}</td>
                <td>{{ $cuota->unidad->codigo ?? 'N/A' }}</td>
                <td>{{ $cuota->residente->nombre_completo ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($cuota->fecha)->format('F Y') }}</td>
                <td>${{ number_format($cuota->monto, 2) }}</td>
                <td>
                    <span class="badge 
        @if($cuota->estado == 'pagado') bg-success
        @elseif($cuota->estado == 'pendiente') bg-warning text-dark
        @elseif($cuota->estado == 'activa') bg-primary
        @elseif($cuota->estado == 'cancelada') bg-danger
        @else bg-secondary
        @endif">
                        {{ ucfirst($cuota->estado) }}
                    </span>
                </td>

                <td>
                    <a href="{{ route('cuotas.show', $cuota->id) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('cuotas.edit', $cuota->id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('cuotas.destroy', $cuota->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta cuota?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay cuotas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $cuotas->appends(request()->query())->links() }}
    </div>
</div>
@endsection