<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::latest()->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'city' => 'required|string|max:100',
        ]);
        Hotel::create($validated);
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created.');
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'city' => 'required|string|max:100',
        ]);
        $hotel->update($validated);
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated.');
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted.');
    }
}