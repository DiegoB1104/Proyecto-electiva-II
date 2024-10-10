<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF; // Importar la fachada de PDF

class ParkingController extends Controller
{
    // Método para mostrar la vista principal del parqueadero con los puestos
    public function index()
    {
        // Obtener todos los puestos de parqueadero
        $spots = ParkingSpot::all();
        return view('parking.index', compact('spots'));
    }

    // Asignar puesto automáticamente y registrar tiempo de entrada
    public function store(Request $request)
    {
        // Validación de placa
        $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:10',
                'regex:/^([A-Z]{3}\d{3}|\d{3}[A-Z]{3}|[A-Z]{3}\d{2}[A-Z])$/',
            ]
        ], [
            'plate_number.regex' => 'El formato de la placa ingresada no es válido. Los formatos aceptados son: "ABC123", "123ABC" o "ABC12D".'
        ]);

        // Verificar si la placa ya está en uso
        $existingSpot = ParkingSpot::where('plate_number', $request->plate_number)->first();
        if ($existingSpot) {
            return redirect()->back()->with('error', 'La placa ya está registrada en un puesto.');
        }

        // Asignar puesto automáticamente y registrar el tiempo de entrada
        $spot = ParkingSpot::where('is_occupied', false)->first();
        if ($spot) {
            $spot->update([
                'plate_number' => $request->plate_number,
                'is_occupied' => true,
                'entry_time' => now(), // Registrar hora de entrada
                'exit_time' => null
            ]);
        } else {
            return redirect()->back()->with('error', 'No hay puestos disponibles.');
        }

        return redirect()->route('parking.index')->with('success', 'Puesto asignado exitosamente.');
    }

    // Asignar puesto manualmente y registrar tiempo de entrada
    public function manualAssign(Request $request)
    {
        // Validación de placa
        $request->validate([
            'spot_id' => 'required|exists:parking_spots,id',
            'plate_number' => [
                'required',
                'string',
                'max:10',
                'regex:/^([A-Z]{3}\d{3}|\d{3}[A-Z]{3}|[A-Z]{3}\d{2}[A-Z])$/',
            ]
        ], [
            'plate_number.regex' => 'El formato de la placa ingresada no es válido. Los formatos aceptados son: "ABC123", "123ABC" o "ABC12D".'
        ]);

        // Verificar si la placa ya está en uso
        $existingSpot = ParkingSpot::where('plate_number', $request->plate_number)->first();
        if ($existingSpot) {
            return redirect()->back()->with('error', 'La placa ya está registrada en un puesto.');
        }

        // Asignar el puesto manualmente
        $spot = ParkingSpot::find($request->spot_id);
        $spot->update([
            'plate_number' => $request->plate_number,
            'is_occupied' => true,
            'entry_time' => now(), // Registrar hora de entrada
            'exit_time' => null
        ]);

        return redirect()->route('parking.index')->with('success', 'Puesto asignado manualmente.');
    }

    // Liberar puesto y registrar tiempo de salida
    public function release(Request $request, $id)
    {
        $spot = ParkingSpot::find($id);
        $spot->update([
            'plate_number' => null,
            'is_occupied' => false,
            'exit_time' => now() // Registrar hora de salida
        ]);

        return redirect()->route('parking.index')->with('success', 'Puesto liberado exitosamente.');
    }

    // Función para generar el reporte en PDF
    public function generateReport()
{
    // Obtener todos los puestos ocupados con tiempos de entrada y salida
    $spots = ParkingSpot::whereNotNull('plate_number')
                        ->whereNotNull('entry_time')
                        ->get();

    // Registrar los datos de los spots en el log
    Log::info('Datos de los spots: ', $spots->toArray()); // Aquí es donde registras los datos

    // Cargar la vista para generar el PDF
    $pdf = PDF::loadView('parking.report', compact('spots'));

    // Descargar el PDF con un nombre personalizado
    return $pdf->download('reporte_placas_'.date('Y-m-d').'.pdf');
}

    // Buscar por número de placa
    public function search(Request $request)
    {
        $searchPlate = $request->input('search_plate');
        $spots = ParkingSpot::where('plate_number', 'LIKE', '%' . $searchPlate . '%')->get();

        if ($spots->isEmpty()) {
            return redirect()->route('parking.index')->with('error', 'No se encontraron resultados para la placa: ' . $searchPlate);
        }

        return view('parking.index', compact('spots'));
    }
}
