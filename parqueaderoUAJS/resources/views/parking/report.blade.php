<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Placas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Cambia el color de la información de las placas a azul */
        td {
            color: black; /* Establece el color azul para las celdas de la tabla */
        }
    </style>
</head>
<body>
    <h1>Reporte de Placas Ingresadas</h1>
    <p>Fecha de generación: {{ date('Y-m-d H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>Número de Placa</th>
                <th>Hora de Entrada</th>
                <th>Hora de Salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($spots as $spot)
                <tr>
                    <td>{{ $spot->plate_number }}</td>
                    <td>{{ $spot->entry_time ? $spot->entry_time->format('Y-m-d H:i:s') : 'No registrado' }}</td>
<td>{{ $spot->exit_time ? $spot->exit_time->format('Y-m-d H:i:s') : 'Aún en el parqueadero' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
