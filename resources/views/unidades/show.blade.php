{{-- resources/views/unidades/show.blade.php --}}
@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Detalle de la Unidad</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Residente:</strong>
                {{ $unidad->residente
                    ? $unidad->residente->nombreCompleto
                    : 'Sin asignar' }}
            </p>
            <p><strong>Código:</strong> {{ $unidad->codigo }}</p>
            <p><strong>Placa:</strong> {{ $unidad->placa ?? '-' }}</p>
            <p><strong>Marca:</strong> {{ $unidad->marca ?? '-' }}</p>
            <p><strong>Capacidad:</strong> {{ $unidad->capacidad }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($unidad->estado) }}</p>
            <p><strong>Personas por Unidad:</strong> {{ $unidad->personas_por_unidad }}</p>
            <p><strong>Tiene Mascotas:</strong> {{ $unidad->tiene_mascotas ? 'Sí' : 'No' }}</p>
            <p><strong>Vehículos:</strong> {{ $unidad->vehiculos }}</p>
        </div>
    </div>

    <a href="{{ route('unidades.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
