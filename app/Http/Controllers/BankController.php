<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * Display list of banks
     */
    public function index()
    {
        $banks = Bank::latest()->get();
        return view('admin.banks.index', compact('banks'));
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
