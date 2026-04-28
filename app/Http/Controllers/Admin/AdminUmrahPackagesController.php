<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminUmrahPackagesController extends Controller
{
    public function index()
    {
        return view('admin.umrah.packages');
    }

    public function data(Request $request)
    {
        abort_unless(Schema::hasTable('umrah_packages'), 404);

        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 10), 100);
        $search = (string) ($request->input('search.value') ?? '');

        $base = DB::table('umrah_packages');
        if ($search !== '') {
            $base->where('package_name', 'like', "%{$search}%");
        }

        $recordsTotal = DB::table('umrah_packages')->count();
        $recordsFiltered = (clone $base)->count();

        $rows = (clone $base)
            ->select([
                'id',
                'package_name',
                'total_days',
                'price_per_person',
                'makkah_hotel',
                'madinah_hotel',
                'group_size',
                'departure_date',
                'return_date',
                'created_at',
            ])
            ->orderByDesc('id')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'package_name' => $p->package_name,
                'total_days' => $p->total_days,
                'price_per_person' => $p->price_per_person,
                'makkah_hotel' => $p->makkah_hotel,
                'madinah_hotel' => $p->madinah_hotel,
                'group_size' => $p->group_size,
                'departure_date' => $p->departure_date,
                'return_date' => $p->return_date,
                'created_at' => (string) ($p->created_at ?? ''),
            ])
            ->all();

        return response()->json(compact('draw', 'recordsTotal', 'recordsFiltered') + ['data' => $rows]);
    }

    public function store(Request $request)
    {
        abort_unless(Schema::hasTable('umrah_packages'), 404);

        $data = $request->validate([
            'package_name' => ['required', 'string', 'max:255'],
            'total_days' => ['required', 'integer', 'min:1'],
            'price_per_person' => ['required', 'numeric', 'min:0'],
            'makkah_hotel' => ['nullable', 'string', 'max:255'],
            'madinah_hotel' => ['nullable', 'string', 'max:255'],
            'group_size' => ['nullable', 'integer', 'min:1'],
            'departure_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date'],
        ]);

        DB::table('umrah_packages')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'Package created.');
    }

    public function update(Request $request, int $package)
    {
        abort_unless(Schema::hasTable('umrah_packages'), 404);

        $data = $request->validate([
            'package_name' => ['required', 'string', 'max:255'],
            'total_days' => ['required', 'integer', 'min:1'],
            'price_per_person' => ['required', 'numeric', 'min:0'],
            'makkah_hotel' => ['nullable', 'string', 'max:255'],
            'madinah_hotel' => ['nullable', 'string', 'max:255'],
            'group_size' => ['nullable', 'integer', 'min:1'],
            'departure_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date'],
        ]);

        DB::table('umrah_packages')->where('id', $package)->update($data + ['updated_at' => now()]);
        return back()->with('success', 'Package updated.');
    }

    public function destroy(int $package)
    {
        abort_unless(Schema::hasTable('umrah_packages'), 404);
        DB::table('umrah_packages')->where('id', $package)->delete();
        return back()->with('success', 'Package deleted.');
    }
}

