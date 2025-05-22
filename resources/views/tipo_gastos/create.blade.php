<h2>Crear Tipo de Gasto</h2>
<form action="{{ route('tipo-gastos.store') }}" method="POST">
    @csrf
    <input type="text" name="nombre" placeholder="Nombre" required>
    <textarea name="descripcion" placeholder="Descripción"></textarea>
    <button type="submit">Guardar</button>
</form>
<a href="{{ route('tipo-gastos.index') }}">Volver</a>
