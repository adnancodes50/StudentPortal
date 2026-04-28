<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminUmrahBookingsController extends Controller
{
    public function index()
    {
        return view('admin.umrah.bookings');
    }

    public function data(Request $request)
    {
        abort_unless(Schema::hasTable('umrah_bookings') && Schema::hasTable('umrah_packages') && Schema::hasTable('bookings'), 404);

        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = DB::table('umrah_bookings as ub')
            ->join('umrah_packages as up', 'up.id', '=', 'ub.package_id')
            ->join('bookings as b', 'b.id', '=', 'ub.booking_id');

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('up.package_name', 'like', "%{$search}%")
                    ->orWhere('b.booking_no', 'like', "%{$search}%")
                    ->orWhere('b.reference_no', 'like', "%{$search}%");
            });
        }

        $recordsTotal = DB::table('umrah_bookings')->count();
        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select(['ub.id', 'ub.booking_id', 'up.package_name', 'b.booking_type', 'b.status', 'b.total_amount', 'ub.created_at'])
            ->orderByDesc('ub.id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'booking_id' => $r->booking_id,
                'package_name' => $r->package_name,
                'booking_type' => $r->booking_type,
                'status' => $r->status,
                'total_amount' => $r->total_amount,
                'created_at' => (string) ($r->created_at ?? ''),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }
}

