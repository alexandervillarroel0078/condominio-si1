@extends('layouts.ap')

@section('content')
<div class="container">
    <h1>Editar Activo</h1>

    <form action="{{ route('inventario.update', $inventario) }}" method="POST">
        @csrf @method('PUT')

        @include('inventario.partials.form', ['inventario' => $inventario])

        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
