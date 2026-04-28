@extends('adminlte::page')

@section('title', 'Bookings')

@section('content_header')
    <h1>Bookings</h1>
@stop

@section('content')
    <div class="card"><div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="filterType" class="form-control">
                    <option value="">All Types</option>
                    <option value="flight">Flight</option>
                    <option value="hotel">Hotel</option>
                    <option value="visa">Visa</option>
                    <option value="group">Group</option>
                    <option value="insurance">Insurance</option>
                    <option value="umrah">Umrah</option>
                    <option value="umrah_group">Umrah Group</option>
                    <option value="package">Package</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-control">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <table id="bookingsTable" class="table table-bordered table-striped">
            <thead><tr><th>ID</th><th>Ref</th><th>Type</th><th>Status</th><th>Total</th><th>Created</th></tr></thead>
        </table>
    </div></div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    const table = $('#bookingsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.bookings.data') }}",
            data: function (d) { d.booking_type = $('#filterType').val(); d.status = $('#filterStatus').val(); }
        },
        columns: [{data:'id'},{data:'reference_no'},{data:'booking_type'},{data:'status'},{data:'total_amount'},{data:'created_at'}]
    });
    $('#filterType, #filterStatus').on('change', function () { table.ajax.reload(); });
});
</script>
@stop

