<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonationController extends Controller
{
    public function start(Request $request, User $user)
    {
        $admin = Auth::user();
        abort_unless(($admin->role ?? $admin->type) === 'admin', 403);

        if ($request->session()->has('impersonator_id')) {
            abort(403, 'Nested impersonation is not allowed.');
        }

        $targetRole = $user->role ?? $user->type;
        abort_unless(in_array($targetRole, ['agent', 'user'], true), 403);

        $request->session()->put('impersonator_id', $admin->id);
        $request->session()->put('impersonated_id', $user->id);
        $request->session()->regenerate();
        Auth::loginUsingId($user->id);

        Log::info('admin_impersonation_start', [
            'admin_id' => $admin->id,
            'target_user_id' => $user->id,
            'target_role' => $targetRole,
            'ip' => $request->ip(),
        ]);

        return redirect()->to($targetRole === 'agent' ? '/agent/dashboard' : '/user/dashboard');
    }

    public function stop(Request $request)
    {
        $impersonatorId = $request->session()->get('impersonator_id');
        $impersonatedId = $request->session()->get('impersonated_id');
        abort_unless($impersonatorId, 403);

        $request->session()->forget(['impersonator_id', 'impersonated_id']);
        $request->session()->regenerate();
        Auth::loginUsingId($impersonatorId);

        Log::info('admin_impersonation_stop', [
            'admin_id' => $impersonatorId,
            'target_user_id' => $impersonatedId,
            'ip' => $request->ip(),
        ]);

        return redirect()->to('/admin/dashboard');
    }
}

