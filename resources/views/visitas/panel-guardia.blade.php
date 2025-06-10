
{{-- resources/views/visitas/panel-guardia.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Panel de Control - Guardia de Seguridad</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('visitas.index') }}" class="btn btn-secondary">Todas las Visitas</a>
        </div>
        <div class="col-md-6 text-end">
            <span class="text-muted">Última actualización: {{ now()->format('d/m/Y H:i:s') }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formulario para validar código --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Validar Código de Visitante</h5>
        </div>
        <div class="card-body">
            <form id="validarCodigoForm">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="codigo" class="form-label">Código (6 dígitos)</label>
                        <input type="text"
                               id="codigo"
                               name="codigo"
                               class="form-control"
                               placeholder="123456"
                               maxlength="6"
                               required>
                    </div>
                    <div class="col-md-4">
                        <label for="ci_visitante" class="form-label">CI del Visitante</label>
                        <input type="text"
                               id="ci_visitante"
                               name="ci_visitante"
                               class="form-control"
                               placeholder="12345678"
                               required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Validar Código</button>
                    </div>
                </div>
            </form>

            {{-- Resultado de la validación --}}
            <div id="resultadoValidacion" class="mt-3" style="display: none;">
                <div class="alert alert-info">
                    <h6>Datos del Visitante:</h6>
                    <div id="datosVisitante"></div>
                    <div class="mt-3">
                        <button id="btnRegistrarEntrada" class="btn btn-success">Registrar Entrada</button>
                        <button id="btnCancelar" class="btn btn-secondary">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Visitas En Curso --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Visitantes Dentro del Condominio ({{ $visitasEnCurso->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($visitasEnCurso as $visita)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="row">
                                <div class="col-8">
                                    <strong>{{ $visita->nombre_visitante }}</strong><br>
                                    <small class="text-muted">CI: {{ $visita->ci_visitante }}</small><br>
                                    <small class="text-muted">Visitando: {{ $visita->residente->nombre_completo }}</small><br>
                                    <small class="text-success">
                                        @if($visita->hora_entrada)
                                            Entrada: {{ $visita->hora_entrada->format('H:i') }}
                                        @else
                                            Entrada: No registrada
                                        @endif
                                    </small>
                                    @if($visita->placa_vehiculo)
                                        <br><small class="text-info">Vehículo: {{ $visita->placa_vehiculo }}</small>
                                    @endif
                                </div>
                                <div class="col-4 text-end">
                                    <form action="{{ route('visitas.salida', $visita) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-sm btn-primary"
                                                onclick="return confirm('¿Registrar salida de {{ $visita->nombre_visitante }}?')">
                                            Registrar Salida
                                        </button>
                                    </form>
                                    <a href="{{ route('visitas.show', $visita) }}" class="btn btn-sm btn-info mt-1">Ver</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No hay visitantes dentro del condominio.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Visitas Pendientes (próximas 2 horas) --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Visitas Pendientes - Próximas 2 Horas ({{ $visitasPendientes->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($visitasPendientes as $visita)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="row">
                                <div class="col-8">
                                    <strong>{{ $visita->nombre_visitante }}</strong><br>
                                    <small class="text-muted">CI: {{ $visita->ci_visitante }}</small><br>
                                    <small class="text-muted">Visitando: {{ $visita->residente->nombre_completo }}</small><br>
                                    <small class="text-primary">
                                        Código: <strong>{{ $visita->codigo }}</strong>
                                    </small><br>
                                    <small class="text-warning">
                                        Horario: {{ $visita->fecha_inicio->format('H:i') }} - {{ $visita->fecha_fin->format('H:i') }}
                                    </small>
                                    @if($visita->placa_vehiculo)
                                        <br><small class="text-info">Vehículo: {{ $visita->placa_vehiculo }}</small>
                                    @endif
                                </div>
                                <div class="col-4 text-end">
                                    <form action="{{ route('visitas.entrada', $visita) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-sm btn-success"
                                                onclick="return confirm('¿Registrar entrada de {{ $visita->nombre_visitante }}?')">
                                            Registrar Entrada
                                        </button>
                                    </form>
                                    <a href="{{ route('visitas.show', $visita) }}" class="btn btn-sm btn-info mt-1">Ver</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No hay visitas programadas para las próximas 2 horas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas del día --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Resumen del Día - {{ now()->format('d/m/Y') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $visitasEnCurso->count() }}</h4>
                            <small>Visitantes Dentro</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $visitasPendientes->count() }}</h4>
                            <small>Visitas Pendientes</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">
                                {{ \App\Models\Visita::whereDate('hora_entrada', today())->count() }}
                            </h4>
                            <small>Entradas Hoy</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">
                                {{ \App\Models\Visita::whereDate('hora_salida', today())->count() }}
                            </h4>
                            <small>Salidas Hoy</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('validarCodigoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const codigo = document.getElementById('codigo').value;
    const ci = document.getElementById('ci_visitante').value;
    
    if (codigo.length !== 6) {
        alert('El código debe tener 6 dígitos');
        return;
    }
    
    fetch('{{ route("visitas.validar-codigo") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            codigo: codigo,
            ci_visitante: ci
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarDatosVisitante(data.visita);
        } else {
            alert(data.message);
            limpiarFormulario();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al validar el código');
    });
});

function mostrarDatosVisitante(visita) {
    const datosDiv = document.getElementById('datosVisitante');
    datosDiv.innerHTML = `
        <strong>Nombre:</strong> ${visita.nombre_visitante}<br>
        <strong>CI:</strong> ${visita.ci_visitante}<br>
        <strong>Residente:</strong> ${visita.residente}<br>
        <strong>Motivo:</strong> ${visita.motivo}<br>
        ${visita.placa_vehiculo ? `<strong>Vehículo:</strong> ${visita.placa_vehiculo}<br>` : ''}
    `;
    
    document.getElementById('resultadoValidacion').style.display = 'block';
    
    // Configurar botón de registrar entrada
    document.getElementById('btnRegistrarEntrada').onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("visitas.entrada", ":id") }}'.replace(':id', visita.id);
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    };
}

function limpiarFormulario() {
    document.getElementById('codigo').value = '';
    document.getElementById('ci_visitante').value = '';
    document.getElementById('resultadoValidacion').style.display = 'none';
}

document.getElementById('btnCancelar').addEventListener('click', limpiarFormulario);

// Auto-refresh cada 30 segundos
setTimeout(function() {
    location.reload();
}, 30000);
</script>
@endsection