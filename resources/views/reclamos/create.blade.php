@extends('plantilla')

@section('title','Nuevo Reclamo o Sugerencia')

@section('content')
<div class="container mt-4">
  <h2>Nuevo Reclamo / Sugerencia</h2>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('reclamos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="tipo" class="form-label">Tipo</label>
      <select name="tipo" id="tipo" class="form-select" required>
        <option value="reclamo"  {{ old('tipo')=='reclamo'  ? 'selected':'' }}>Reclamo</option>
        <option value="sugerencia"{{ old('tipo')=='sugerencia'? 'selected':'' }}>Sugerencia</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="titulo" class="form-label">Título</label>
      <input type="text" name="titulo" id="titulo"
             class="form-control" required
             value="{{ old('titulo') }}">
    </div>

    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción</label>
      <textarea name="descripcion" id="descripcion"
                class="form-control" rows="5"
                required>{{ old('descripcion') }}</textarea>
    </div>

    <div class="mb-3">
      <label for="adjunto" class="form-label">Adjunto (opcional)</label>
      <input type="file" name="adjunto" id="adjunto"
             class="form-control" accept=".jpg,.jpeg,.png,.pdf">
      <div class="form-text">
        envia solo un archivo ya sea: jpg, jpeg, png o pdf (máx. 2MB).
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
    <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
@endsection
