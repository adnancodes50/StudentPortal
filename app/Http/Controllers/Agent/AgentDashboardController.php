<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AgentDashboardController extends Controller
{
    public function index(): View
    {
        $agentId = auth()->id();

        $base = Booking::query()->where('agent_id', $agentId);

        $statusCounts = (clone $base)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $typeCounts = (clone $base)
            ->select('booking_type', DB::raw('COUNT(*) as total'))
            ->groupBy('booking_type')
            ->pluck('total', 'booking_type');

        $stats = [
            'bookings' => (clone $base)->count(),
            'pending_bookings' => (int) ($statusCounts['pending'] ?? 0),
            'draft_bookings' => (int) ($statusCounts['draft'] ?? 0),
            'confirmed_bookings' => (int) ($statusCounts['confirmed'] ?? 0),
            'cancelled_bookings' => (int) ($statusCounts['cancelled'] ?? 0),
            'total_revenue' => (float) (clone $base)->where('status', 'confirmed')->sum('total_amount'),
            'wallet_balance' => 0.0,
        ];

        $recentTransactions = collect();
        if (Schema::hasTable('wallet_transactions')) {
            $recentTransactions = DB::table('wallet_transactions')
                ->where('agent_id', $agentId)
                ->select(['id', 'type', 'amount', 'balance_after', 'reference_type', 'reference_id', 'created_at'])
                ->latest('id')
                ->limit(10)
                ->get()
                ->map(function ($tx) {
                    $methods = ['Bank Transfer', 'Local Account', 'JazzCash', 'EasyPaisa', 'Cash Deposit'];
                    $seed = (string) ($tx->reference_type ?? 'manual') . '-' . (string) ($tx->reference_id ?? $tx->id);
                    $index = abs(crc32($seed)) % count($methods);
                    $method = $methods[$index];

                    return (object) [
                        'id' => $tx->id,
                        'method' => $method,
                        'type' => $tx->type,
                        'amount' => (float) $tx->amount,
                        'balance_after' => (float) $tx->balance_after,
                        'created_at' => $tx->created_at,
                    ];
                });

            $latestBalance = DB::table('wallet_transactions')
                ->where('agent_id', $agentId)
                ->orderByDesc('id')
                ->value('balance_after');

            $stats['wallet_balance'] = (float) ($latestBalance ?? 0);
        }

        $recentBookings = (clone $base)
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

        return view('agent.dashboard', compact('stats', 'recentBookings', 'recentTransactions', 'statusChart', 'typeChart'));
    }
}

