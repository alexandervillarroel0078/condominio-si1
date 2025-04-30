@extends('layouts.ap')

@section('content')
<div class="container">
    <h2>Registrar Cargo</h2>

    <form action="{{ route('cargos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="cargo" class="form-label">Nombre del Cargo</label>
            <input type="text" name="cargo" id="cargo" class="form-control" required>
        </div>
        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('cargos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
