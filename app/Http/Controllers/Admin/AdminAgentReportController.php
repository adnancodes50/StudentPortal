<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminAgentReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.agents.index');
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = User::query()->where('role', 'agent');
        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $recordsTotal = User::query()->where('role', 'agent')->count();
        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select(['id', 'name', 'email', 'phone', 'status', 'created_at'])
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
                'status' => $u->status,
                'created_at' => optional($u->created_at)->toDateTimeString(),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }

    public function show(User $agent)
    {
        abort_unless(($agent->role ?? $agent->type) === 'agent', 404);

        $bookings = Booking::query()->where('agent_id', $agent->id);
        $statusCounts = (clone $bookings)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $typeCounts = (clone $bookings)
            ->select('booking_type', DB::raw('COUNT(*) as total'))
            ->groupBy('booking_type')
            ->pluck('total', 'booking_type');

        $receivedAmount = 0.0;
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'agent_id')) {
            $receivedAmount = (float) DB::table('payments')
                ->where('agent_id', $agent->id)
                ->where('status', 'completed')
                ->sum('amount');
        }

        $stats = [
            'total_bookings' => (clone $bookings)->count(),
            'total_amount' => (float) (clone $bookings)->sum('total_amount'),
            'received_amount' => $receivedAmount,
            'pending' => (int) ($statusCounts['pending'] ?? 0),
            'draft' => (int) ($statusCounts['draft'] ?? 0),
            'confirmed' => (int) ($statusCounts['confirmed'] ?? 0),
            'cancelled' => (int) ($statusCounts['cancelled'] ?? 0),
        ];

        $recentBookings = (clone $bookings)
            ->select(['id', 'reference_no', 'booking_no', 'booking_type', 'status', 'total_amount', 'created_at'])
            ->latest()
            ->limit(10)
            ->get();

        $statusChart = [
            'labels' => ['Pending', 'Draft', 'Confirmed', 'Cancelled'],
            'values' => [$stats['pending'], $stats['draft'], $stats['confirmed'], $stats['cancelled']],
        ];

        $typeChart = [
            'labels' => $typeCounts->keys()->values()->all(),
            'values' => $typeCounts->values()->all(),
        ];

        return view('admin.reports.agents.show', compact('agent', 'stats', 'recentBookings', 'statusChart', 'typeChart'));
    }
}

