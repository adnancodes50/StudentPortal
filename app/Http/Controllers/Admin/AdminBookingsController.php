<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingsController extends Controller
{
    public function index()
    {
        return view('admin.bookings.index');
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $type = $request->input('booking_type');
        $status = $request->input('status');

        $base = Booking::query();
        if ($type) {
            $base->where('booking_type', $type);
        }
        if ($status) {
            $base->where('status', $status);
        }
        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('booking_no', 'like', "%{$search}%");
            });
        }

        $recordsTotal = Booking::query()->count();
        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select(['id', 'reference_no', 'booking_no', 'booking_type', 'status', 'total_amount', 'created_at'])
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn (Booking $b) => [
                'id' => $b->id,
                'reference_no' => $b->reference_no ?? $b->booking_no,
                'booking_type' => $b->booking_type,
                'status' => $b->status,
                'total_amount' => $b->total_amount,
                'created_at' => optional($b->created_at)->toDateTimeString(),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }
}

