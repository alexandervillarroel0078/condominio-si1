@extends('layouts.ap')

@section('content')
<div class="container">
    <h3>Detalle del Inventario</h3>
    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $inventario->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $inventario->descripcion }}</p>
            <p><strong>Estado:</strong> {{ $inventario->estado }}</p>
            <p><strong>Valor estimado:</strong> Bs {{ number_format($inventario->valor_estimado, 2) }}</p>
            <p><strong>Fecha de adquisición:</strong> {{ \Carbon\Carbon::parse($inventario->fecha_adquisicion)->format('d/m/Y') }}</p>

            <p><strong>Tipo de adquisición:</strong> {{ $inventario->tipo_adquisicion }}</p>
            <p><strong>Área común:</strong> {{ $inventario->areaComun->nombre ?? 'Sin asignar' }}</p>
            <p><strong>Usuario que registró:</strong> {{ $inventario->user->name ?? 'No disponible' }}</p>
            <a href="{{ route('inventario.index') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection
