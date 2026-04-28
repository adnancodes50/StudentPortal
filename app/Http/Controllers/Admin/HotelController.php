<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('rooms')->latest()->paginate(15);
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

    public function storeRoom(Request $request, $hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $data = $request->validate([
            'room_name' => 'required|string|max:100',
            'room_type' => 'required|in:single,double,triple,quad,family,suite',
            'bed_capacity' => 'required|integer|min:1|max:10',
            'availability' => 'required|in:available,unavailable',
        ]);

        $room = $hotel->rooms()->create($data);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Room added to hotel.',
                'room' => $room,
                'total_rooms' => $hotel->rooms()->count(),
            ]);
        }

        return redirect()->route('admin.hotels.show', $hotel->id)->with('success', 'Room added to hotel.');
    }

    public function destroyRoom(Request $request, $roomId)
    {
        $room = Room::findOrFail($roomId);
        $hotelId = $room->hotel_id;
        $room->delete();

        if ($request->ajax()) {
            $totalRooms = Room::where('hotel_id', $hotelId)->count();
            return response()->json([
                'message' => 'Room deleted.',
                'total_rooms' => $totalRooms,
            ]);
        }

        return redirect()->route('admin.hotels.show', $hotelId)->with('success', 'Room deleted.');
    }

    public function show($id)
    {
        $hotel = Hotel::withCount('rooms')->findOrFail($id);
        return view('admin.hotels.show', compact('hotel'));
    }

    public function roomsData($hotelId)
    {
        Hotel::findOrFail($hotelId);

        $rooms = Room::query()
            ->where('hotel_id', $hotelId)
            ->select(['id', 'room_name', 'room_type', 'bed_capacity', 'availability'])
            ->latest('id')
            ->get();

        return response()->json(['data' => $rooms]);
    }
}