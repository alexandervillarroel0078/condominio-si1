@extends('layouts.ap')

@section('content')
<div class="container">
    <h1>Registrar Activo</h1>

    <form action="{{ route('inventario.store') }}" method="POST">
        @csrf

        @include('inventario.partials.form')

        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
