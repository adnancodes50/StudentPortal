<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightPrice;
use App\Models\Category;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
        $flights = Flight::with('flightPrices')->get();
        return view('admin.flights.index', compact('flights'));
    }

    public function create()
{
$categories = Category::all();
// add($categories);
    return view('admin.flights.create', compact('categories'));
}

    public function store(Request $request)
    {
        $request->validate([
            'airline_name' => 'required',
            'flight_number' => 'required',
            'departure_city' => 'required',
            'arrival_city' => 'required',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'category_id' => 'required',

            // Price validation
            'seat_class' => 'required',
            'price' => 'required|numeric',
        ]);

        $flight = Flight::create($request->only([
            'airline_name','flight_number','departure_city',
            'arrival_city','departure_time','arrival_time','category_id'
        ]));

        // Save price
        FlightPrice::create([
            'flight_id' => $flight->id,
            'seat_class' => $request->seat_class,
            'price' => $request->price,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.flights.index')->with('success', 'Flight Created');
    }

    public function edit($id)
    {
        $flight = Flight::with('flightPrices')->findOrFail($id);
        $categories = Category::all();

        return view('admin.flights.edit', compact('flight','categories'));
    }

    public function update(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);

        $flight->update($request->only([
            'airline_name','flight_number','departure_city',
            'arrival_city','departure_time','arrival_time','category_id'
        ]));

        // Update or create price
        FlightPrice::updateOrCreate(
            ['flight_id' => $flight->id],
            [
                'seat_class' => $request->seat_class,
                'price' => $request->price,
                'valid_from' => $request->valid_from,
                'valid_to' => $request->valid_to,
                'status' => $request->status,
            ]
        );

        return redirect()->route('admin.flights.index')->with('success', 'Flight Updated');
    }

    public function destroy($id)
    {
        Flight::findOrFail($id)->delete();
        return back()->with('success', 'Deleted');
    }
}