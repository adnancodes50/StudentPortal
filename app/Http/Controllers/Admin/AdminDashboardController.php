<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $statusCounts = Booking::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $typeCounts = Booking::query()
            ->select('booking_type', DB::raw('COUNT(*) as total'))
            ->groupBy('booking_type')
            ->pluck('total', 'booking_type');

        $stats = [
            'agents' => User::query()->where('role', 'agent')->count(),
            'users' => User::query()->where('role', 'user')->count(),
            'bookings' => Booking::query()->count(),
            'pending_bookings' => (int) ($statusCounts['pending'] ?? 0),
            'draft_bookings' => (int) ($statusCounts['draft'] ?? 0),
            'confirmed_bookings' => Booking::query()->where('status', 'confirmed')->count(),
            'cancelled_bookings' => (int) ($statusCounts['cancelled'] ?? 0),
            'total_revenue' => (float) Booking::query()->where('status', 'confirmed')->sum('total_amount'),
        ];

        $recentBookings = Booking::query()
            ->select(['id', 'reference_no', 'booking_no', 'booking_type', 'status', 'total_amount', 'created_at'])
            ->latest()
            ->limit(10)
            ->get();

        $statusChart = [
            'labels' => ['Pending', 'Draft', 'Confirmed', 'Cancelled'],
            'values' => [
                (int) ($statusCounts['pending'] ?? 0),
                (int) ($statusCounts['draft'] ?? 0),
                (int) ($statusCounts['confirmed'] ?? 0),
                (int) ($statusCounts['cancelled'] ?? 0),
            ],
        ];

        $typeChart = [
            'labels' => $typeCounts->keys()->values()->all(),
            'values' => $typeCounts->values()->all(),
        ];

        return view('admin.dashboard', compact('stats', 'recentBookings', 'statusChart', 'typeChart'));
    }
}

