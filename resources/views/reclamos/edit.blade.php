@extends('plantilla')

@section('title', 'Editar Reclamo/Sugerencia')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Reclamo/Sugerencia</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ups!</strong> Hay errores en el formulario.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('reclamos.update', $reclamo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="reclamo"   {{ old('tipo', $reclamo->tipo)=='reclamo'   ? 'selected' : '' }}>Reclamo</option>
                <option value="sugerencia"{{ old('tipo', $reclamo->tipo)=='sugerencia'? 'selected' : '' }}>Sugerencia</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo"
                   class="form-control"
                   value="{{ old('titulo', $reclamo->titulo) }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4"
                      class="form-control" required>{{ old('descripcion', $reclamo->descripcion) }}</textarea>
        </div>

        {{-- Si ya hay un adjunto, muéstralo y ofrece descargar --}}
        @if($reclamo->adjunto)
        @php
            $url = Storage::url($reclamo->adjunto);
            $ext = pathinfo($reclamo->adjunto, PATHINFO_EXTENSION);
        @endphp
        <div class="mb-3">
            <label class="form-label">Adjunto actual</label>
            <div>
                <a href="{{ $url }}" target="_blank">
                    @if(in_array($ext, ['jpg','jpeg','png','gif']))
                        <img src="{{ $url }}" style="max-height:100px;">
                    @elseif($ext==='pdf')
                        <i class="fas fa-file-pdf fa-2x"></i>
                    @else
                        <i class="fas fa-file fa-2x"></i>
                    @endif
                        Ver
                </a>
            </div>
        </div>
        @endif

        <div class="mb-3">
            <label for="adjunto" class="form-label">Reemplazar Adjunto (opcional)</label>
            <input type="file" name="adjunto" id="adjunto"
                   class="form-control"
                   accept=".jpg,.jpeg,.png,.pdf">
            <div class="form-text">
                jpg, png o pdf (máx. 2MB). Si subes uno nuevo, el anterior se eliminará.
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
