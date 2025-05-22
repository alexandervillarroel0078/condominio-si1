@extends('plantilla')

@section('title', 'Gastos')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}"
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4">Gastos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Gastos</li>
        </ol>

        <div class="mb-4">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Nuevo Gasto
            </button>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-table me-1"></i> Tabla de Gastos</div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Tipo de Gasto</th>
                            <th>Monto (Bs)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gastos as $gasto)
                            <tr>
                                <td>{{ $gasto->concepto }}</td>
                                <td>{{ $gasto->tipoGasto->nombre }}</td>
                                <td>{{ number_format($gasto->monto, 2) }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditar-{{ $gasto->id }}">Editar</button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmarEliminarGasto-{{ $gasto->id }}">Eliminar</button>

                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEditar-{{ $gasto->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('gastos.update', $gasto->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Gasto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Concepto</label>
                                                    <input type="text" name="concepto" value="{{ $gasto->concepto }}"
                                                        class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Tipo de Gasto</label>
                                                    <select name="tipo_gasto_id" class="form-control" required>
                                                        @foreach ($tipos as $tipo)
                                                            <option value="{{ $tipo->id }}"
                                                                {{ $tipo->id == $gasto->tipo_gasto_id ? 'selected' : '' }}>
                                                                {{ $tipo->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Monto (Bs)</label>
                                                    <input type="number" name="monto" value="{{ $gasto->monto }}"
                                                        step="0.01" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary btn-sm" type="submit">Actualizar</button>
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Modal Eliminar Gasto -->
                            <div class="modal fade" id="confirmarEliminarGasto-{{ $gasto->id }}" tabindex="-1"
                                aria-labelledby="confirmarEliminarLabel-{{ $gasto->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Eliminar Gasto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Deseas eliminar el gasto: <strong>{{ $gasto->concepto }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('gastos.destroy', $gasto->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary btn-sm">Aceptar</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('gastos.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Gasto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Concepto</label>
                            <input type="text" name="concepto" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tipo de Gasto</label>
                            <select name="tipo_gasto_id" class="form-control" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Monto (Bs)</label>
                            <input type="number" name="monto" step="0.01" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
