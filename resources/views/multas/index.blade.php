@extends('plantilla')

@section('title', 'Panel de Multas')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
@if (session('success'))
    <script>
        Swal.fire({
            toast: true,
            position: "top-end",
            icon: "success",
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">Panel de Multas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Multas</li>
    </ol>

    {{-- Solo administradores pueden crear nuevas multas --}}
    @if(auth()->check() && !auth()->user()->residente_id && !auth()->user()->empleado_id)
    <div class="mb-4">
        <a href="{{ route('multas.create') }}" class="btn btn-primary btn-sm">Nueva Multa</a>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Tabla Multas</div>
        <div class="card-body table-responsive">
            <table id="datatablesMultas" class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Motivo</th>
                        <th>Monto (Bs.)</th>
                        <th>Emitida</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($multas as $multa)
                    <tr>
                        <td>
                            {{ optional($multa->residente)->nombre_completo
                            ?? optional($multa->empleado)->nombre_completo
                            ?? 'N/A' }}
                        </td>
                        <td>{{ $multa->motivo }}</td>
                        <td>{{ number_format($multa->monto, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($multa->fechaEmision)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($multa->fechaLimite)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge
                                @if($multa->estado == 'pendiente') bg-warning text-dark
                                @elseif($multa->estado == 'pagada') bg-success
                                @elseif($multa->estado == 'anulada') bg-danger
                                @elseif($multa->estado == 'apelada') bg-info text-dark
                                @else bg-secondary text-white
                                @endif">
                                {{ ucfirst($multa->estado) }}
                            </span>
                        </td>
                        <td>

                            <div class="btn-group" role="group">
                                {{-- Botón “Pagar” para residentes y empleados --}}
                                @if(auth()->check() && (auth()->user()->residente_id || auth()->user()->empleado_id) && $multa->estado == 'pendiente')
                                    <a href="{{ route('pagos.create', ['multa_id' => $multa->id]) }}"
                                       class="btn btn-success btn-sm me-1">
                                       Pagar
                                    </a>
                                @endif

                                {{-- Solo administradores pueden editar/anular --}}
                                @if(auth()->check() && !auth()->user()->residente_id && !auth()->user()->empleado_id)
                                    <a href="{{ route('multas.edit', $multa->id) }}" class="btn btn-warning btn-sm me-1">Editar</a>
                                    <!-- Botón Anular -->
                                    <form action="{{ route('multas.destroy', $multa->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Eliminar esta multa?')">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        new simpleDatatables.DataTable("#datatablesMultas");
    });
</script>
@endpush
