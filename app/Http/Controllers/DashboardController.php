<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function home(): RedirectResponse
    {
        $authUser = auth()->user();
        $role = $authUser->role ?? $authUser->type;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'agent' => redirect()->route('agent.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }

    public function admin(): View
    {
        return $this->sharedDashboard('admin');
    }

    public function agent(): View
    {
        return $this->sharedDashboard('agent');
    }

    public function user(): View
    {
        $authUser = auth()->guard('user')->user() ?? auth()->user();

        $bookingCount = Booking::where('user_id', $authUser->id)->count();

        return view('dashboard.user', compact('bookingCount'));
    }

    private function sharedDashboard(string $role): View
    {
        $authUser = auth()->guard($role)->user() ?? auth()->user();

        $bookingsQuery = Booking::query();

        if ($role === 'agent') {
            $bookingsQuery->where('agent_id', $authUser->id);
        }

        $bookingCount = $bookingsQuery->count();
        $passengerCount = $role === 'admin' ? User::where('role', 'user')->count() : 0;
        $agentCount = $role === 'admin' ? User::where('role', 'agent')->count() : 0;

        return view('dashboard.shared', compact('bookingCount', 'passengerCount', 'agentCount'));
    }
}
