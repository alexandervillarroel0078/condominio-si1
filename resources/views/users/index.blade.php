@extends('plantilla')

@section('title', 'Usuarios')

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
    <h1 class="mt-4">Usuarios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}"> Inicio </a></li>
        <li class="breadcrumb-item active">Usuarios</li>
    </ol>
    @can('crear usuarios')
    <div class="mb-4">
        <a href="{{ route('users.create') }}"><button type="button" class="btn btn-primary btn-sm">Nuevo
                usuario</button></a>
    </div>
    @endcan
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla Usuarios
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            {{ $user->name }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                @can('editar usuarios')
                                <form action="{{ route('users.edit', ['user' => $user]) }}"
                                    method="GET">
                                    <button type="submit" class="btn btn-primary btn-sm">Editar</button>
                                </form>
                                @endcan
                                <form action=""
                                    method="GET" hidden>
                                    <button type="submit" class="btn btn-warning btn-sm">Cambiar password</button>
                                </form>
                                
                                
                                @can('eliminar usuarios')
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#confimarModal-{{ $user->id }}">Eliminar</button>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal -->
                    @can('eliminar usuarios')
                    <div class="modal fade" id="confimarModal-{{ $user->id }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="confimarModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confimarModalLabel">Eliminar usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿ Desea eliminar el registro de: {{ $user->name }} ?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('users.destroy', ['user' => $user->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-primary btn-sm">Aceptar</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush