<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Parqueadero - UAJS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .btn {
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn:hover {
            transform: scale(1.1);
        }
        .alert {
            margin-top: 20px;
        }
        h1, h2 {
            color: black;
        }

        .form-inline {
            margin-bottom: 15px;
        }

        .btn-info {
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gestión de Parqueadero - UAJS</h1>

        <div class="text-center mb-4">
            <img src="{{ asset('images/LOGO_CORPOSUCRE_VERTICAL.jpg') }}" alt="Logo" class="img-fluid" style="max-width: 300px;">
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Mostrar los errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h2>Registrar Placa</h2>
                <form action="{{ route('parking.assign') }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="plate_number" class="sr-only">Número de Placa:</label>
                        <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="Ingrese número de placa" required style="text-transform: uppercase;">
                    </div>
                    <button type="submit" class="btn btn-success mb-2 ml-2">Asignar Automáticamente</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="mt-4">Asignación Manual</h2>
                <form action="{{ route('parking.assign.manual') }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="spot_id" class="sr-only">Selecciona un Puesto:</label>
                        <select class="form-control" id="spot_id" name="spot_id" required>
                            @foreach ($spots as $spot)
                                @if (!$spot->is_occupied)
                                    <option value="{{ $spot->id }}">{{ $spot->id }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2 ml-2">
                        <label for="plate_number_manual" class="sr-only">Número de Placa:</label>
                        <input type="text" class="form-control" id="plate_number_manual" name="plate_number" placeholder="Ingrese número de placa" required style="text-transform: uppercase;">
                    </div>
                    <button type="submit" class="btn btn-success mb-2 ml-2">Asignar Manualmente</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="mt-4">Estado de los Puestos</h2>
                <div class="mb-4">
                    <form action="{{ route('parking.search') }}" method="GET" class="form-inline">
                        <input type="text" class="form-control mr-2" id="search_plate" name="search_plate" placeholder="Buscar placa" required style="text-transform: uppercase;">
                        <button type="submit" class="btn btn-info">Buscar</button>
                    </form>

                    <!-- Mostrar botón de regresar al inicio solo después de la búsqueda -->
                    @if (isset($searchPlate))
                        <a href="{{ route('parking.index') }}" class="btn btn-warning mt-2">Volver al Inicio</a>
                    @endif

                    <div class="row mt-4">
                        @foreach ($spots as $spot)
                            <div class="col-md-2">
                                <div class="card mb-3" style="background-color: {{ $spot->is_occupied ? 'red' : 'green' }}; color: white;">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Puesto {{ $spot->id }}</h5>
                                        @if ($spot->is_occupied)
                                            <p class="card-text">Placa: {{ $spot->plate_number }}</p>
                                            <form action="{{ route('parking.release', $spot->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">Liberar</button>
                                            </form>
                                        @else
                                            <p class="card-text">Libre</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validar mayúsculas en los inputs de placa
            document.getElementById('plate_number').addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase();
            });
            document.getElementById('plate_number_manual').addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase();
            });
        });
    </script>
</body>
</html>
