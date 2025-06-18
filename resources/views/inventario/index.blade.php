@extends('layouts.ap')

@section('content')
<div class="container">
    <h1>Inventario</h1>

    <a href="{{ route('inventario.create') }}" class="btn btn-success mb-3">Nuevo Activo</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORMULARIO DE FILTRO --}}
    <form method="GET" action="{{ route('inventario.index') }}" class="card p-3 mb-3">
    <div class="row g-2">
            <div class="col-md-3">
                <label>Desde</label>
                <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
            </div>
            <div class="col-md-3">
                <label>Hasta</label>
                <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
            </div>
            <div class="col-md-2">
                <label>Categoría</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Área común</label>
                <select name="area_comun_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ request('area_comun_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-3">
                <div class="form-check">
                    <input type="checkbox" name="bajo_valor" class="form-check-input" {{ request('bajo_valor') ? 'checked' : '' }}>
                    <label class="form-check-label">Bajo valor</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="por_vencer" class="form-check-input" {{ request('por_vencer') ? 'checked' : '' }}>
                    <label class="form-check-label">Por vencer</label>
                </div>
            </div>
            <div class="col-12 mt-2">
                <button class="btn btn-primary">Aplicar filtros</button>
                <a href="{{ route('inventario.index') }}" class="btn btn-link">Limpiar</a>
            </div>
        </div>
    </form>

    {{-- TABLA --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Responsable</th>
                <th>Área común</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inventarios as $item)
                <tr>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->categoria->nombre }}</td>
                    <td>{{ $item->estado }}</td>
                    <td>{{ $item->responsable->name ?? 'No asignado' }}</td>
                    <td>{{ $item->areaComun->nombre ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('inventario.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('inventario.destroy', $item) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar este activo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                        <a href="{{ route('inventario.show', $item->id) }}" class="btn btn-sm btn-info">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No se encontraron resultados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
