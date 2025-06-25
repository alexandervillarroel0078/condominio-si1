@extends('plantilla')

@section('title', 'Panel de Reclamos y Sugerencias')

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
    <h1 class="mt-4">Panel de Reclamos y Sugerencias</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Reclamos y Sugerencias</li>
    </ol>

    {{-- residentes y empleados pueden crear reclamos/sugerencias --}}
    @if(auth()->check() && (auth()->user()->residente_id || auth()->user()->empleado_id))
    <div class="mb-4">
        <a href="{{ route('reclamos.create') }}" class="btn btn-primary btn-sm">Nueva Solicitud
            <i class="fas fa-plus"></i>
        </a>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="datatablesReclamos" class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Título</th>
                        <th>Emision</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reclamos as $rec)
                    <tr>
                        <td>{{ $rec->id }}</td>
                        <td>
                            {{ optional($rec->residente)->nombre_completo
                              ?? optional($rec->empleado)->nombre_completo
                              ?? 'N/A' }}
                        </td>
                        <td>{{ ucfirst($rec->tipo) }}</td>
                        <td>{{ $rec->titulo }}</td>
                        <td>{{ \Carbon\Carbon::parse($rec->fechaCreacion)->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge
                                @if($rec->estado == 'pendiente') bg-warning text-dark
                                @elseif($rec->estado == 'abierto')   bg-info text-dark
                                @elseif($rec->estado == 'resuelto') bg-success
                                @else                                bg-secondary text-white
                                @endif
                            ">
                                {{ ucfirst($rec->estado) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                {{-- Ver detalles del reclamo/sugerencia --}}
                                    <a href="{{ route('reclamos.show', $rec->id) }}"
                                    class="btn btn-sm btn-info me-1">
                                        Ver
                                    </a>
                                {{-- Solo residentes y empleados pueden editar/borrar --}}
                                @if(auth()->check() && auth()->user()->residente_id || auth()->user()->empleado_id)
                                    @if($rec->estado == 'pendiente')
                                        <a href="{{ route('reclamos.edit', $rec->id) }}"
                                        class="btn btn-sm btn-warning me-1">
                                            Editar
                                        </a>
                                        <form action="{{ route('reclamos.destroy', $rec->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmarEliminar-{{ $rec->id }}">
                                            Eliminar</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    <!-- Modal de confirmación de eliminación -->
                    <div class="modal fade" id="confirmarEliminar-{{ $rec->id }}" tabindex="-1" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmarEliminarLabel">Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar este reclamo/sugerencia?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <form action="{{ route('reclamos.destroy', $rec->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            Eliminar
                                        </button>
                                    </form>
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
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        new simpleDatatables.DataTable("#datatablesReclamos");
    });
</script>
@endpush
