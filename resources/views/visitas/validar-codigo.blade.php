{{-- resources/views/visitas/validar-codigo.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 text-center">Validar Código de Visitante</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Formulario para validar código --}}
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-center">Ingrese los datos del visitante</h5>
                </div>
                <div class="card-body">
                    <form id="validarCodigoForm">
                        @csrf
                        <div class="mb-4">
                            <label for="codigo" class="form-label">Código de Visita (6 dígitos)</label>
                            <input type="text"
                                   id="codigo"
                                   name="codigo"
                                   class="form-control form-control-lg text-center"
                                   placeholder="123456"
                                   maxlength="6"
                                   style="font-size: 2rem; letter-spacing: 0.5rem;"
                                   required>
                            <div class="form-text">Código proporcionado por el residente</div>
                        </div>

                        <div class="mb-4">
                            <label for="ci_visitante" class="form-label">Cédula de Identidad del Visitante</label>
                            <input type="text"
                                   id="ci_visitante"
                                   name="ci_visitante"
                                   class="form-control form-control-lg"
                                   placeholder="12345678"
                                   required>
                            <div class="form-text">CI que debe coincidir con el registro</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="btnValidar">
                                <i class="fas fa-check-circle"></i> Validar Código
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </form>

                    {{-- Resultado de la validación --}}
                    <div id="resultadoValidacion" class="mt-4" style="display: none;">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-user-check"></i> Visitante Validado</h5>
                            <div id="datosVisitante"></div>
                            <hr>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <button id="btnRegistrarEntrada" class="btn btn-success btn-lg me-md-2">
                                    <i class="fas fa-sign-in-alt"></i> Registrar Entrada
                                </button>
                                <button onclick="limpiarFormulario()" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Mensaje de error --}}
                    <div id="errorValidacion" class="mt-4" style="display: none;">
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> Error de Validación</h5>
                            <p id="mensajeError"></p>
                            <button onclick="limpiarFormulario()" class="btn btn-outline-danger">
                                Intentar Nuevamente
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel de información --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Instrucciones</h6>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li>Solicite el código de 6 dígitos al visitante</li>
                                <li>Verifique la cédula de identidad</li>
                                <li>Los datos deben coincidir exactamente</li>
                                <li>Valide que esté dentro del horario autorizado</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">Accesos Rápidos</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('visitas.panel-guardia') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-tachometer-alt"></i> Panel de Guardia
                                </a>
                                <a href="{{ route('visitas.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list"></i> Todas las Visitas
                                </a>
                            </div>
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
    const btnValidar = document.getElementById('btnValidar');
    
    // Validaciones del lado cliente
    if (codigo.length !== 6) {
        mostrarError('El código debe tener exactamente 6 dígitos');
        return;
    }
    
    if (!ci.trim()) {
        mostrarError('Debe ingresar la cédula de identidad');
        return;
    }
    
    // Mostrar loading
    btnValidar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validando...';
    btnValidar.disabled = true;
    
    // Ocultar resultados anteriores
    document.getElementById('resultadoValidacion').style.display = 'none';
    document.getElementById('errorValidacion').style.display = 'none';
    
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
            mostrarError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarError('Error de conexión. Intente nuevamente.');
    })
    .finally(() => {
        // Restaurar botón
        btnValidar.innerHTML = '<i class="fas fa-check-circle"></i> Validar Código';
        btnValidar.disabled = false;
    });
});

function mostrarDatosVisitante(visita) {
    const datosDiv = document.getElementById('datosVisitante');
    datosDiv.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <strong>Nombre:</strong> ${visita.nombre_visitante}<br>
                <strong>CI:</strong> ${visita.ci_visitante}<br>
                <strong>Motivo:</strong> ${visita.motivo}
            </div>
            <div class="col-md-6">
                <strong>Residente:</strong> ${visita.residente}<br>
                ${visita.placa_vehiculo ? `<strong>Vehículo:</strong> ${visita.placa_vehiculo}<br>` : ''}
                <strong>Código:</strong> <span class="text-primary">${document.getElementById('codigo').value}</span>
            </div>
        </div>
    `;
    
    document.getElementById('resultadoValidacion').style.display = 'block';
    
    // Configurar botón de registrar entrada
    document.getElementById('btnRegistrarEntrada').onclick = function() {
        if (confirm(`¿Registrar entrada de ${visita.nombre_visitante}?`)) {
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
        }
    };
}

function mostrarError(mensaje) {
    document.getElementById('mensajeError').textContent = mensaje;
    document.getElementById('errorValidacion').style.display = 'block';
}

function limpiarFormulario() {
    document.getElementById('codigo').value = '';
    document.getElementById('ci_visitante').value = '';
    document.getElementById('resultadoValidacion').style.display = 'none';
    document.getElementById('errorValidacion').style.display = 'none';
    document.getElementById('codigo').focus();
}

// Auto-focus en el campo código al cargar
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('codigo').focus();
});

// Solo números en el campo código
document.getElementById('codigo').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endsection