<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightPrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FlightController extends Controller
{
    public function index()
    {
        return view('admin.flights.index');
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = Flight::query();
        $recordsTotal = (clone $base)->count();

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('airline_name', 'like', "%{$search}%")
                    ->orWhere('flight_number', 'like', "%{$search}%")
                    ->orWhere('departure_city', 'like', "%{$search}%")
                    ->orWhere('arrival_city', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->with(['flightPrices' => fn ($q) => $q->orderByDesc('id')])
            ->select([
                'id',
                'airline_name',
                'flight_number',
                'departure_city',
                'arrival_city',
                'departure_time',
                'status',
                'created_at',
            ])
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function (Flight $f) {
                $economy = $f->flightPrices->firstWhere('seat_class', 'economy');
                $business = $f->flightPrices->firstWhere('seat_class', 'business');
                $first = $f->flightPrices->firstWhere('seat_class', 'first');

                $formatPrice = static function ($price) {
                    if (!$price) {
                        return null;
                    }

                    return ($price->currency ?? 'PKR') . ' ' . number_format((float) $price->price, 2);
                };

                return [
                    'id' => $f->id,
                    'airline_name' => $f->airline_name,
                    'flight_number' => $f->flight_number,
                    'route' => $f->departure_city . ' -> ' . $f->arrival_city,
                    'departure_time' => $f->departure_time,
                    'status' => $f->status ?? 'scheduled',
                    'prices' => [
                        'economy' => $formatPrice($economy),
                        'business' => $formatPrice($business),
                        'first' => $formatPrice($first),
                    ],
                    'created_at' => optional($f->created_at)->toDateTimeString(),
                ];
            })
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }

    public function create()
    {
        return view('admin.flights.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->flightRules());

        $flight = Flight::create([
            'airline_name' => $validated['airline_name'],
            'flight_number' => $validated['flight_number'],
            'departure_city' => $validated['departure_city'],
            'arrival_city' => $validated['arrival_city'],
            'departure_date' => $validated['departure_date'],
            'return_date' => $validated['return_date'] ?? null,
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $validated['arrival_time'],
            'baggage_kg' => $validated['baggage_kg'],
            'origin_airport_code' => $validated['origin_airport_code'] ?? null,
            'destination_airport_code' => $validated['destination_airport_code'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'status' => $validated['status'] ?? 'scheduled',
        ]);

        return redirect()->route('admin.flights.show', $flight->id)->with('success', 'Flight created. Now add prices below.');
    }

    public function edit($id)
    {
        $flight = Flight::findOrFail($id);
        return view('admin.flights.edit', compact('flight'));
    }

    public function update(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);
        $validated = $request->validate($this->flightRules($flight->id));

        $flight->update([
            'airline_name' => $validated['airline_name'],
            'flight_number' => $validated['flight_number'],
            'departure_city' => $validated['departure_city'],
            'arrival_city' => $validated['arrival_city'],
            'departure_date' => $validated['departure_date'],
            'return_date' => $validated['return_date'] ?? null,
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $validated['arrival_time'],
            'baggage_kg' => $validated['baggage_kg'],
            'origin_airport_code' => $validated['origin_airport_code'] ?? null,
            'destination_airport_code' => $validated['destination_airport_code'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'status' => $validated['status'] ?? 'scheduled',
        ]);

        return redirect()->route('admin.flights.show', $flight->id)->with('success', 'Flight updated.');
    }

    public function show($id)
    {
        $flight = Flight::with(['flightPrices' => fn($q) => $q->latest()])->findOrFail($id);
        return view('admin.flights.show', compact('flight'));
    }

    public function storePrice(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);
        $validated = $request->validate($this->priceRules());
        $validated['is_refundable'] = $request->boolean('is_refundable');

        $flight->flightPrices()->create($validated);

        return redirect()->route('admin.flights.show', $flight->id)->with('success', 'Price added.');
    }

    public function updatePrice(Request $request, $id, $priceId)
    {
        $flight = Flight::findOrFail($id);
        $price = FlightPrice::where('flight_id', $flight->id)->findOrFail($priceId);
        $validated = $request->validate($this->priceRules());
        $validated['is_refundable'] = $request->boolean('is_refundable');

        $price->update($validated);

        return redirect()->route('admin.flights.show', $flight->id)->with('success', 'Price updated.');
    }

    public function destroyPrice($id, $priceId)
    {
        $flight = Flight::findOrFail($id);
        $price = FlightPrice::where('flight_id', $flight->id)->findOrFail($priceId);
        $price->delete();

        return redirect()->route('admin.flights.show', $flight->id)->with('success', 'Price deleted.');
    }

    public function destroy($id)
    {
        Flight::findOrFail($id)->delete();
        return back()->with('success', 'Flight deleted.');
    }

    private function flightRules(?int $flightId = null): array
    {
        return [
            'airline_name' => ['required', 'string', 'max:100'],
            'flight_number' => ['required', 'string', 'max:50', Rule::unique('flights', 'flight_number')->ignore($flightId)],
            'departure_city' => ['required', 'string', 'max:100'],
            'arrival_city' => ['required', 'string', 'max:100'],
            'departure_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:departure_date'],
            'departure_time' => ['required', 'date'],
            'arrival_time' => ['required', 'date', 'after:departure_time'],
            'baggage_kg' => ['required', 'integer', 'min:0', 'max:100'],
            'origin_airport_code' => ['nullable', 'string', 'max:10'],
            'destination_airport_code' => ['nullable', 'string', 'max:10'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:2000'],
            'status' => ['required', 'in:scheduled,delayed,cancelled'],
        ];
    }

    private function priceRules(): array
    {
        return [
            'seat_class' => ['required', 'in:economy,business,first'],
            'price' => ['required', 'numeric', 'min:0'],
            'valid_from' => ['required', 'date'],
            'valid_to' => ['required', 'date', 'after_or_equal:valid_from'],
            'status' => ['required', 'in:active,inactive'],
            'currency' => ['nullable', 'string', 'max:10'],
            'is_refundable' => ['nullable', 'boolean'],
        ];
    }
}
