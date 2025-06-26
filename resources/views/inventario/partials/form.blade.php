@php
$inventario = $inventario ?? null;
@endphp

<div class="row">
    <div class="col-md-6">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $inventario->nombre ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label>Categoría</label>
        <select name="categoria_id" class="form-control" required>
            <option value="">Seleccione</option>
            @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" {{ old('categoria_id', $inventario->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->nombre }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
    <label>Estado</label>
    <select name="estado" class="form-control" required>
        @php
            $estados = ['disponible', 'en_uso', 'mantenimiento', 'prestado', 'extraviado', 'baja'];
        @endphp
        @foreach($estados as $estado)
            <option value="{{ $estado }}" {{ old('estado', $inventario->estado ?? 'disponible') == $estado ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $estado)) }}
            </option>
        @endforeach
    </select>
</div>

    <div class="col-md-6">
        <label>Tipo de adquisición</label>
        <input type="text" name="tipo_adquisicion" class="form-control" value="{{ old('tipo_adquisicion', $inventario->tipo_adquisicion ?? '') }}">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <label>Valor estimado (Bs)</label>
        <input type="number" step="0.01" name="valor_estimado" class="form-control" value="{{ old('valor_estimado', $inventario->valor_estimado ?? '') }}">
    </div>
    <div class="col-md-6">
        <label>Ubicación</label>
        <input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion', $inventario->ubicacion ?? '') }}">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <label>Área común</label>
        <select name="area_comun_id" class="form-control">
            <option value="">Sin asignar</option>
            @foreach($areas as $area)
            <option value="{{ $area->id }}" {{ old('area_comun_id', $inventario->area_comun_id ?? '') == $area->id ? 'selected' : '' }}>
                {{ $area->nombre }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label>Fecha de adquisición</label>
        <input type="text" class="form-control"
            value="{{ isset($inventario)
                    ? $inventario->fecha_adquisicion->format('d/m/Y')
                    : now()->format('d/m/Y') }}"
            readonly>

    </div>


</div>