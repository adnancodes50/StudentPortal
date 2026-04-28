<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminWalletController extends Controller
{
    public function index()
    {
        return view('admin.wallet.index');
    }

    public function data(Request $request)
    {
        if (Schema::hasTable('wallet_transactions')) {
            return $this->walletTransactionsData($request);
        }
        if (Schema::hasTable('ledger')) {
            return $this->ledgerData($request);
        }
        abort(404);
    }

    private function walletTransactionsData(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = DB::table('wallet_transactions');
        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('reference_type', 'like', "%{$search}%")
                    ->orWhere('agent_id', $search);
            });
        }

        $recordsTotal = DB::table('wallet_transactions')->count();
        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select(['id', 'agent_id', 'type', 'amount', 'balance_after', 'reference_type', 'reference_id', 'created_at'])
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'agent_id' => $t->agent_id,
                'type' => $t->type,
                'amount' => $t->amount,
                'balance_after' => $t->balance_after,
                'reference_type' => $t->reference_type,
                'reference_id' => $t->reference_id,
                'created_at' => (string) ($t->created_at ?? ''),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }

    private function ledgerData(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = DB::table('ledger');
        if ($search !== '') {
            $base->where('booking_id', $search);
        }

        $recordsTotal = DB::table('ledger')->count();
        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select(['id', 'booking_id', 'debit', 'credit', 'created_at'])
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn ($l) => [
                'id' => $l->id,
                'booking_id' => $l->booking_id,
                'type' => $l->credit > 0 ? 'credit' : 'debit',
                'amount' => $l->credit > 0 ? $l->credit : $l->debit,
                'balance_after' => null,
                'reference_type' => 'booking',
                'reference_id' => $l->booking_id,
                'created_at' => (string) ($l->created_at ?? ''),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }
}

