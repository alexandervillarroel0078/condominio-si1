@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Reservas - Áreas Comunes</h2>

    {{-- Botón para agendar una nueva reserva SOLO para residentes --}}
    @if(auth()->check() && auth()->user()->residente_id)
        <a href="{{ route('reservas.create') }}" class="btn btn-primary mb-3">Agendar Nueva Reserva</a>
    @endif

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Reserva</th>
                <th>Nombre Área</th>
                <th>Monto (Bs.)</th>
                <th>Estado Área</th>
                <th>Fecha Reserva</th>
                <th>Hora Reserva</th>
                <th>Residente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $reserva)
            <tr>
                <td>{{ $reserva->id }}</td>
                <td>{{ $reserva->areaComun->nombre ?? 'N/D' }}</td>
                <td>{{ number_format($reserva->areaComun->monto ?? 0, 2) }}</td>
                <td>
                    <span class="badge
                        @if(strtolower($reserva->areaComun->estado ?? '') == 'libre') bg-secondary
                        @elseif(strtolower($reserva->areaComun->estado ?? '') == 'ocupado') bg-success
                        @elseif(strtolower($reserva->areaComun->estado ?? '') == 'mantenimiento') bg-warning text-dark
                        @else bg-light text-dark
                        @endif
                    ">
                        {{ ucfirst($reserva->areaComun->estado ?? 'N/D') }}
                    </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</td>
                <td>{{ $reserva->residente->nombre ?? 'N/D' }}</td>
                <td>
                    {{-- Botón Editar SOLO para residentes --}}
                    @if(auth()->check() && auth()->user()->residente_id)
                        <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-sm btn-warning mb-1">Editar Reserva</a>
                    @endif

                    {{-- Botón Eliminar (decide si lo quieres para todos o también solo para residentes) --}}
                    <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta reserva?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $reservas->links() }}
    </div>
</div>
@endsection
