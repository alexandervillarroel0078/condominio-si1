<h2>Editar Tipo de Gasto</h2>
<form action="{{ route('tipo-gastos.update', $tipoGasto) }}" method="POST">
    @csrf @method('PUT')
    <input type="text" name="nombre" value="{{ $tipoGasto->nombre }}" required>
    <textarea name="descripcion">{{ $tipoGasto->descripcion }}</textarea>
    <button type="submit">Actualizar</button>
</form>
<a href="{{ route('tipo-gastos.index') }}">Volver</a>
