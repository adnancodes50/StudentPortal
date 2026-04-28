@extends('adminlte::page')

@section('title', 'Umrah Bookings')

@section('content_header')
    <h1>Umrah Bookings</h1>
@stop

@section('content')
    <div class="card"><div class="card-body">
        <table id="umrahBookingsTable" class="table table-bordered table-striped">
            <thead><tr><th>ID</th><th>Booking ID</th><th>Package</th><th>Type</th><th>Status</th><th>Total</th><th>Created</th></tr></thead>
        </table>
    </div></div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#umrahBookingsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.umrah.bookings.data') }}" },
        columns: [{data:'id'},{data:'booking_id'},{data:'package_name'},{data:'booking_type'},{data:'status'},{data:'total_amount'},{data:'created_at'}]
    });
});
</script>
@stop

