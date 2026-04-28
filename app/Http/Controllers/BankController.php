<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Schema;

class BankController extends Controller
{
    /**
     * Display list of banks
     */
    public function index()
    {
        return view('admin.banks.index');
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = Bank::query();
        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%");
                if (Schema::hasColumn('banks', 'account_title')) {
                    $q->orWhere('account_title', 'like', "%{$search}%");
                }
                if (Schema::hasColumn('banks', 'account_number')) {
                    $q->orWhere('account_number', 'like', "%{$search}%");
                }
            });
        }

        $recordsTotal = Bank::query()->count();
        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select([
                'id',
                'bank_name',
                ...(Schema::hasColumn('banks', 'account_title') ? ['account_title'] : []),
                ...(Schema::hasColumn('banks', 'account_number') ? ['account_number'] : []),
                'created_at',
            ])
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn (Bank $bank) => [
                'id' => $bank->id,
                'bank_name' => $bank->bank_name,
                'account_title' => $bank->account_title ?? '-',
                'account_number' => $bank->account_number ?? '-',
                'created_at' => optional($bank->created_at)->toDateTimeString(),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }

    /**
     * Store new bank
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name'       => 'required|string|max:100',
            'account_title'   => 'required|string|max:100',
            'account_number'  => 'required|string|max:50',
        ]);

        Bank::create([
            'bank_name'      => $request->bank_name,
            'account_title'  => $request->account_title,
            'account_number' => $request->account_number,
        ]);

        return back()->with('success', 'Bank added successfully');
    }

    /**
     * Update bank
     */
    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $request->validate([
            'bank_name'       => 'required|string|max:100',
            'account_title'   => 'required|string|max:100',
            'account_number'  => 'required|string|max:50',
        ]);

        $bank->update([
            'bank_name'      => $request->bank_name,
            'account_title'  => $request->account_title,
            'account_number' => $request->account_number,
        ]);

        return back()->with('success', 'Bank updated successfully');
    }

    /**
     * Delete bank
     */
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return back()->with('success', 'Bank deleted successfully');
    }
}
