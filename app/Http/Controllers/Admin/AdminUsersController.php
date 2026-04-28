<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = User::query()->whereIn('role', ['user', 'agent']);
        $recordsTotal = (clone $base)->count();

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select(['id', 'name', 'email', 'phone', 'status', 'role', 'created_at'])
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
                'role' => $u->role ?? $u->type,
                'created_at' => optional($u->created_at)->toDateTimeString(),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }

    public function show(User $user)
    {
        abort_unless(in_array(($user->role ?? $user->type), ['user', 'agent'], true), 403);

        return response()->json([
            'id' => $user->id,
            'role' => $user->role ?? $user->type,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
            'passport_no' => $user->passport_no,
            'address' => $user->address,
            'created_at' => optional($user->created_at)->toDateTimeString(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['user', 'agent'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'passport_no' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [
            'role' => $data['role'],
            'type' => 'user', // legacy enum compatibility
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
            'passport_no' => $data['passport_no'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        User::create($payload);

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, User $user)
    {
        abort_unless(in_array(($user->role ?? $user->type), ['user', 'agent'], true), 403);

        $data = $request->validate([
            'role' => ['required', Rule::in(['user', 'agent'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'passport_no' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->role = $data['role'];
        $user->type = 'user';
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->status = $data['status'];
        $user->passport_no = $data['passport_no'] ?? null;
        $user->address = $data['address'] ?? null;
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return response()->json(['ok' => true]);
    }

    public function destroy(User $user)
    {
        abort_unless(in_array(($user->role ?? $user->type), ['user', 'agent'], true), 403);
        $user->delete();
        return response()->json(['ok' => true]);
    }
}

