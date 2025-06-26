<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h3 style="text-align:center">Inventario filtrado</h3>
    <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Categoría</th>
                <th>Área</th>
                <th>Valor Bs</th>
                <th>Fecha Adq.</th>
                <th>Ubicación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventarios as $inv)
            <tr>
                <td>{{ $inv->id }}</td>
                <td>{{ $inv->nombre }}</td>
                <td>{{ $inv->estado }}</td>
                <td>{{ $inv->categoria->nombre ?? '' }}</td>
                <td>{{ $inv->areaComun->nombre ?? '' }}</td>
                <td>{{ $inv->valor_estimado }}</td>
                <td>{{ optional($inv->fecha_adquisicion)->format('d/m/Y') }}</td>
                <td>{{ $inv->ubicacion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
