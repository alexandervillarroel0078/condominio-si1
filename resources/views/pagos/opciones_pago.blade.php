@extends('layouts.ap')

@section('title', 'Pagar cuota')

@section('content')
<div class="container mt-4">
    <h4>Pago de Cuota: {{ $cuota->concepto }} (Bs {{ $cuota->monto }})</h4>

    <div class="row">
        <div class="col-md-6">
            <h5>Pago por QR</h5>
            <p>Escanee este código y suba su comprobante.</p>

            {{-- Mostrar QR generado dinámicamente --}}
            <img src="{{ $qrBase64 }}" width="200" alt="QR de pago">

            <form method="POST" action="{{ route('pagos.qr') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="cuota_id" value="{{ $cuota->id }}">
                <input type="file" name="comprobante" class="form-control mb-2" required>
                <button type="submit" class="btn btn-primary btn-sm">Enviar comprobante</button>
            </form>
        </div>


        <div class="col-md-6">
            <h5>Pago con tarjeta (Stripe)</h5>
            <p>Haz clic para pagar de forma automática.</p>
            <form method="POST" action="{{ route('pagos.stripe') }}">
                @csrf
                <input type="hidden" name="cuota_id" value="{{ $cuota->id }}">
                <button type="submit" class="btn btn-success btn-sm">Pagar con Stripe</button>
            </form>
        </div>
    </div>
</div>
@endsection