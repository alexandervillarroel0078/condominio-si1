@extends('layouts.ap')

@section('content')
<div class="container">
    <h2 class="mb-4">Catálogo de Áreas Comunes</h2>

    {{-- Mostrar botón NUEVA ÁREA COMÚN solo si NO es residente --}}
    @if(auth()->check() && !auth()->user()->residente_id)
        <a href="{{ route('areas-comunes.create') }}" class="btn btn-primary mb-3">Nueva Área Común</a>
    @endif

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Monto/Hora (Bs.)</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($areasComunes as $area)
            <tr>
                <td>{{ $area->id }}</td>
                <td>{{ $area->nombre }}</td>
                <td>{{ number_format($area->monto, 2) }}</td>
                <td>
                    <span class="badge
                        @if(strtolower($area->estado) == 'libre') bg-secondary
                        @elseif(strtolower($area->estado) == 'ocupado') bg-success
                        @elseif(strtolower($area->estado) == 'mantenimiento') bg-warning text-dark
                        @else bg-light text-dark
                        @endif
                    ">
                        {{ ucfirst($area->estado) }}
                    </span>
                </td>
                <td>
                    {{-- Mostrar botones Editar y Eliminar solo si NO es residente --}}
                    @if(auth()->check() && !auth()->user()->residente_id)
                        <a href="{{ route('areas-comunes.edit', $area->id) }}" class="btn btn-sm btn-warning">Editar</a>

                        <form action="{{ route('areas-comunes.destroy', $area->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta área común?')">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Para paginación si la usas --}}
    {{--<div class="mt-3">
        {{ $areasComunes->links() }}
    </div>--}}
</div>
@endsection
