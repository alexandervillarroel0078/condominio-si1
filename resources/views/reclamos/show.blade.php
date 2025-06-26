@extends('plantilla')

@section('title', "Reclamo #{$reclamo->id}")

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
@if (session('success'))
  <script>
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: "success",
      title: "{{ session('success') }}",
      showConfirmButton: false,
      timer: 1500
    });
  </script>
@endif

<div class="container-fluid px-4">
  <h1 class="mt-4">Reclamo #{{ $reclamo->id }}</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reclamos.index') }}">Reclamos</a></li>
    <li class="breadcrumb-item active">Detalle</li>
  </ol>

  <div class="card mb-4">
    <div class="card-body">
      <p><strong>Tipo:</strong> {{ ucfirst($reclamo->tipo) }}</p>
      <p><strong>Título:</strong> {{ $reclamo->titulo }}</p>
      <p><strong>Descripción:</strong><br>{{ $reclamo->descripcion }}</p>



        <!-- Previsualización pequeña -->
        @if($reclamo->adjunto)
        @php
            $url = Storage::url($reclamo->adjunto);
            $ext = pathinfo($reclamo->adjunto, PATHINFO_EXTENSION);
        @endphp

        <div class="card mb-4">
            <div class="card-header" style="font-weight: bold;">
            Adjunto:
            </div>
            <div class="card-body">
            <div class="mb-3">
                {{-- Este div abre el modal --}}
                <div class="ratio ratio-16x9" style="max-width: 300px; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#previewModal">

                @if(in_array($ext, ['jpg','jpeg','png','gif']))
                    <img src="{{ $url }}" class="img-fluid" alt="Adjunto">

                @elseif(in_array($ext, ['mp4','webm','ogg']))
                    <video src="{{ $url }}" class="w-100 h-100" muted loop></video>

                @elseif($ext === 'pdf')
                    {{-- Desactivamos los eventos para que pase el clic al padre --}}
                    <iframe src="{{ $url }}" class="w-100 h-100"
                            style="pointer-events: none;"></iframe>

                @else
                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                    <i class="bi bi-file-earmark-text" style="font-size:2rem;"></i>
                    </div>
                @endif

                </div>
                <small class="text-muted">Haz clic para ampliar</small>
            </div>

            {{-- Botón de descarga --}}
            <a href="{{ $url }}" download class="btn btn-outline-primary btn-sm">
                <i class="bi bi-download"></i> Descargar
            </a>
            </div>
        </div>

        <!-- Modal de previsualización -->
        <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <!-- Uso modal-fullscreen para ocupar toda la pantalla -->
                <div class="modal-content bg-transparent border-0 h-100 d-flex flex-column">

                    <!-- Modal header transparente opcional "btn btn-danger position-absolute top-0 end-0 m-3"-->
                    <div class="modal-header border-0">
                        <button type="button"
                                class="btn-close ms-auto"
                                data-bs-dismiss="modal"
                                aria-label="Cerrar"
                                style="background-color: #dc3545;"></button>
                    </div>

                    <!-- Modal body con flex para que el embed crezca -->
                    <div class="modal-body p-0 flex-fill">
                        @if(in_array($ext, ['jpg','jpeg','png','gif']))
                        <img src="{{ $url }}" class="img-fluid w-100 h-100 object-fit-contain">
                        @elseif(in_array($ext, ['mp4','webm','ogg']))
                        <video src="{{ $url }}" class="w-100 h-100" controls autoplay style="object-fit: contain;"></video>
                        @elseif($ext === 'pdf')
                        <embed src="{{ $url }}"
                                type="application/pdf"
                                width="100%"
                                height="100%"
                                style="border: none;">
                        @else
                        <div class="text-center py-5 bg-white rounded">
                            <i class="bi bi-file-earmark-text" style="font-size:4rem;"></i>
                            <p class="mt-3">
                            No hay vista previa disponible.<br>
                            <a href="{{ $url }}" target="_blank">Descargar archivo</a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
      <p><strong>Creado:</strong> {{ \Carbon\Carbon::parse($reclamo->fechaCreacion)->format('d/m/Y H:i') }}</p>
      <p><strong>Estado:</strong> {{ ucfirst($reclamo->estado) }}</p>
    </div>
  </div>

  @if($reclamo->estado !== 'resuelto')
    @if(auth()->check() && ! auth()->user()->residente_id && ! auth()->user()->empleado_id)
        <div class="card mb-4">
            <div class="card-header">Responder</div>
            <div class="card-body">
            <form action="{{ route('reclamos.respuesta', $reclamo->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                <label for="respuesta" class="form-label">Respuesta</label>
                <textarea name="respuesta" id="respuesta"
                            class="form-control" rows="4">{{ old('respuesta', $reclamo->respuesta) }}</textarea>
                @error('respuesta')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                </div>
                <button type="submit" class="btn btn-success">Guardar Respuesta</button>
                <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">Volver</a>
            </form>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
          <strong>Atención:</strong> Este reclamo aún no ha sido resuelto.
        </div>
        <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">Volver</a>
    @endif
  @else
    <div class="alert alert-info">
      <strong>Respuesta:</strong><br>{{ $reclamo->respuesta }}
    </div>
    <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">Volver</a>
  @endif
</div>
@endsection
