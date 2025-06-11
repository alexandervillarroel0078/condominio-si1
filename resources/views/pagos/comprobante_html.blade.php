<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; }
        .titulo { text-align: center; font-size: 20px; margin-bottom: 20px; }
        .linea { margin-bottom: 10px; }
        .btn-imprimir { margin-top: 20px; text-align: center; }
        .btn-imprimir button { padding: 8px 16px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="titulo">Comprobante de Pago</div>

        <div class="linea"><strong>ID de Pago:</strong> {{ $pago->id }}</div>
        <div class="linea"><strong>Monto:</strong> Bs {{ number_format($pago->monto_pagado, 2) }}</div>
        <div class="linea"><strong>Fecha:</strong> {{ $pago->fecha_pago }}</div>
        <div class="linea"><strong>Método:</strong> {{ ucfirst($pago->metodo) }}</div>
        <div class="linea"><strong>Estado:</strong> {{ ucfirst($pago->estado) }}</div>
        <div class="linea"><strong>Concepto:</strong> {{ $pago->cuota->titulo ?? '-' }}</div>
        <div class="linea"><strong>Residente:</strong> {{ $pago->cuota->residente->nombre ?? '-' }}</div>

        @if($pago->observacion)
        <div class="linea"><strong>Observación:</strong> {{ $pago->observacion }}</div>
        @endif

        <div class="btn-imprimir">
            <button onclick="window.print()">Imprimir / Guardar como PDF</button>
        </div>
    </div>
</body>
</html>
