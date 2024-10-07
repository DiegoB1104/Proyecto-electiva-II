<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function index()
    {
        $spots = ParkingSpot::all();
        return view('parking.index', compact('spots'));
    }

    public function store(Request $request)
    {
        // Validar la placa con mensajes personalizados
        $request->validate([
            'plate_number' => [
                'required', 
                'string', 
                'max:10',
                'regex:/^[A-Z]{3}\d{3}$|^[A-Z]{2}\d{4}$|^[A-Z]{3}\d{2}[A-Z]{1}$/'
            ]
        ], [
            'plate_number.regex' => 'El formato de la placa ingresada no es válido. Los formatos aceptados son: 
            "ABC123", "ABC12D".'
        ]);

        // Verificar si la placa ya está en uso
        $existingSpot = ParkingSpot::where('plate_number', $request->plate_number)->first();
        if ($existingSpot) {
            return redirect()->back()->with('error', 'La placa ya está registrada en un puesto.');
        }

        // Asignar puesto automáticamente
        $spot = ParkingSpot::where('is_occupied', false)->first();
        if ($spot) {
            $spot->update(['plate_number' => $request->plate_number, 'is_occupied' => true]);
        } else {
            return redirect()->back()->with('error', 'No hay puestos disponibles');
        }

        return redirect()->route('parking.index')->with('success', 'Puesto asignado exitosamente');
    }

    public function manualAssign(Request $request)
    {
        // Validar la placa con mensajes personalizados
        $request->validate([
            'spot_id' => 'required|exists:parking_spots,id',
            'plate_number' => [
                'required', 
                'string', 
                'max:10',
                'regex:/^[A-Z]{3}\d{3}$|^[A-Z]{2}\d{4}$|^[A-Z]{3}\d{2}[A-Z]{1}$/'
            ]
        ], [
            'plate_number.regex' => 'El formato de la placa ingresada no es válido. Los formatos aceptados son: 
            "ABC123", "AB1234" o "ABC12D".'
        ]);

        $spot = ParkingSpot::find($request->spot_id);
        $spot->update(['plate_number' => $request->plate_number, 'is_occupied' => true]);

        return redirect()->route('parking.index')->with('success', 'Puesto asignado manualmente');
    }

    public function release(Request $request, $id)
    {
        $spot = ParkingSpot::find($id);
        $spot->update(['plate_number' => null, 'is_occupied' => false]);

        return redirect()->route('parking.index')->with('success', 'Puesto liberado exitosamente');
    }

    public function search(Request $request)
    {
        $searchPlate = $request->input('search_plate');

        // Buscar el puesto relacionado con la placa ingresada
        $spots = ParkingSpot::where('plate_number', 'LIKE', '%' . $searchPlate . '%')->get();

        // Si no se encuentra, redirigir con un mensaje
        if ($spots->isEmpty()) {
            return redirect()->route('parking.index')->with('error', 'No se encontraron resultados para la placa: ' . $searchPlate);
        }

        // Devolver la vista con los resultados de la búsqueda
        return view('parking.index', compact('spots', 'searchPlate'));
    }
}
