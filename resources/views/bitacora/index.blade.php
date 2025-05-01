@extends('layouts.ap')

@section('content')
<div class="container">
    <h1 class="mb-4">Bitácora del Sistema</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Fecha y Hora</th>
                <th>Descripción</th>
                <th>IP</th> {{-- NUEVA COLUMNA --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($bitacoras as $log)
            <tr>
                <td>{{ $log->usuario ?? 'Invitado' }}</td>

                <td>{{ $log->accion }}</td>
                <td>{{ $log->fecha_hora }}</td>
                <td>{{ $log->descripcion ?? '-' }}</td>
                <td>{{ $log->ip ?? '-' }}</td> {{-- NUEVA CELDA --}}
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection