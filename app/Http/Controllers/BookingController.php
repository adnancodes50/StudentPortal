<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $authUser = auth()->user();
        $role = $authUser->role ?? $authUser->type;

        $bookings = Booking::query()
            ->when($role === 'agent', fn ($query) => $query->where('agent_id', $authUser->id))
            ->when($role === 'user', fn ($query) => $query->where('user_id', $authUser->id))
            ->latest()
            ->paginate(15);

        return view('bookings.index', compact('bookings', 'role'));
    }

    public function store(Request $request): RedirectResponse
    {
        $authUser = auth()->user();
        $role = $authUser->role ?? $authUser->type;

        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'booking_type' => ['required', 'in:flight,hotel,visa,group,insurance,umrah,umrah_group'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:draft,confirmed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['booking_no'] = $data['booking_no'] ?? ('BKG-' . now()->format('YmdHis'));
        $data['reference_no'] = 'REF-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        $data['status'] = $data['status'] ?? 'draft';

        if ($role === 'agent') {
            $data['agent_id'] = $authUser->id;
            $data['user_id'] = $request->integer('user_id') ?: null;
        }

        if ($role === 'user') {
            $data['user_id'] = $authUser->id;
            $data['agent_id'] = null;
        }

        Booking::create($data);

        return back()->with('success', 'Booking created successfully.');
    }
}
