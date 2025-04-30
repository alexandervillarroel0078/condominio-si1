@extends('plantilla')

@section('title', 'Crear usuario')

@push('css')
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Crear usuario</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>

        <div class="from-orange-200">
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <label for="name">Usuario</label>
                    <input type="text" class="form-control" name="name" id="name"
                        placeholder="introduzca un nombre de usuario" value="{{ old('name') }}">
                    @error('name')
                        <small class="text-danger"> {{ '*' . $message }} </small>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="email">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" id="email"
                        placeholder="nombre@ejemplo.com" value="{{ old('email') }}">
                    @error('email')
                        <small class="text-danger"> {{ '*' . $message }} </small>
                    @enderror
                </div>

                <div class="form-group mb-2">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control" name="password" id="inputPassword">
                    @error('password')
                        <small class="text-danger"> {{ '*' . $message }} </small>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="empleado_id">Empleado</label>
                    <select class="form-control" name="empleado_id" id="empleado_id">
                        <option value="">--Seleccione empleado--</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}">{{ $empleado->ci }}  {{ $empleado->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="role_id">Rol usuario</label>
                    <select class="form-control" name="role_id" id="role_id">
                        <option value="">--Seleccione Rol de usuario--</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}"> {{ $rol->descripcion }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm"> Guardar </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
   
@endpush
