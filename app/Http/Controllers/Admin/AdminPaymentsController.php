<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminPaymentsController extends Controller
{
    public function index()
    {
        return view('admin.payments.index');
    }

    public function data(Request $request)
    {
        abort_unless(Schema::hasTable('payments'), 404);

        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');
        $status = $request->input('status');

        $base = DB::table('payments');
        if ($status) {
            $base->where('status', $status);
        }
        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }

        $recordsTotal = DB::table('payments')->count();
        $recordsFiltered = (clone $base)->count();

        $columns = ['id', 'booking_id', 'amount', 'status', 'created_at'];
        foreach (['method', 'transaction_id', 'proof_image', 'agent_id'] as $col) {
            if (Schema::hasColumn('payments', $col)) {
                $columns[] = $col;
            }
        }

        $rows = (clone $base)
            ->select($columns)
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'booking_id' => $p->booking_id ?? null,
                'agent_id' => $p->agent_id ?? null,
                'amount' => $p->amount,
                'method' => $p->method ?? null,
                'transaction_id' => $p->transaction_id ?? null,
                'status' => $p->status,
                'proof_image' => $p->proof_image ?? null,
                'created_at' => (string) ($p->created_at ?? ''),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }

    public function setStatus(Request $request, int $payment)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        DB::table('payments')->where('id', $payment)->update([
            'status' => $data['status'],
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}

