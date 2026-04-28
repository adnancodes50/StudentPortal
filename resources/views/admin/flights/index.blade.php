@extends('adminlte::page')

@section('title', 'Flights')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Flight Management</h1>
        <a href="{{ route('admin.flights.create') }}" class="btn btn-primary btn-sm">
            Create Flight
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="flightsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Airline</th>
                        <th>Flight #</th>
                        <th>Route</th>
                        <th>Departs</th>
                        <th>Status</th>
                        <th>Prices</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    const table = $('#flightsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.flights.data') }}" },
        columns: [
            { data: 'id' },
            { data: 'airline_name' },
            { data: 'flight_number' },
            { data: 'route' },
            { data: 'departure_time' },
            { data: 'status', render: function (value) {
                const s = String(value || 'scheduled').toLowerCase();
                const cls = s === 'scheduled' ? 'badge bg-success' : (s === 'delayed' ? 'badge bg-warning' : 'badge bg-danger');
                return `<span class="${cls}">${s.charAt(0).toUpperCase() + s.slice(1)}</span>`;
            }},
            { data: 'prices', render: function (prices) {
                const eco = prices?.economy ? `<div><span class="badge bg-info mr-1">Economy</span>${prices.economy}</div>` : '<div><span class="badge bg-info mr-1">Economy</span>-</div>';
                const bus = prices?.business ? `<div><span class="badge bg-primary mr-1">Business</span>${prices.business}</div>` : '<div><span class="badge bg-primary mr-1">Business</span>-</div>';
                const fst = prices?.first ? `<div><span class="badge bg-warning mr-1">First</span>${prices.first}</div>` : '<div><span class="badge bg-warning mr-1">First</span>-</div>';
                return eco + bus + fst;
            }},
            { data: 'created_at' },
            { data: null, orderable: false, searchable: false, render: function (row) {
                const viewBtn = `<a class="btn btn-sm btn-info" href="/admin/flights/${row.id}" title="View"><i class="fas fa-eye"></i></a>`;
                const editBtn = `<a class="btn btn-sm btn-warning ml-1" href="/admin/flights/edit/${row.id}" title="Edit"><i class="fas fa-edit"></i></a>`;
                const delBtn = `<button class="btn btn-sm btn-danger ml-1 deleteBtn" data-id="${row.id}" title="Delete"><i class="fas fa-trash"></i></button>`;
                return viewBtn + editBtn + delBtn;
            }},
        ]
    });

    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Delete this flight?')) return;
        $.post(`/admin/flights/delete/${id}`, {_token: '{{ csrf_token() }}', _method: 'DELETE'})
            .done(function () { table.ajax.reload(null, false); });
    });
});
</script>
@stop
