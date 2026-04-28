@extends('adminlte::page')

@section('title', 'Payments')

@section('content_header')
    <h1>Payments</h1>
@stop

@section('content')
    <div class="card"><div class="card-body">
        <div class="row mb-3"><div class="col-md-3">
            <select id="filterStatus" class="form-control">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div></div>
        <table id="paymentsTable" class="table table-bordered table-striped">
            <thead><tr><th>ID</th><th>Booking</th><th>Agent</th><th>Amount</th><th>Method</th><th>Txn</th><th>Status</th><th>Proof</th><th>Created</th></tr></thead>
        </table>
    </div></div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    const table = $('#paymentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.payments.data') }}", data: function (d) { d.status = $('#filterStatus').val(); } },
        columns: [
            {data:'id'},{data:'booking_id'},{data:'agent_id'},{data:'amount'},{data:'method'},{data:'transaction_id'},{data:'status'},
            {data:'proof_image',orderable:false,searchable:false,render:function(v){return v?`<a href="/storage/${v}" target="_blank">View</a>`:'-';}},
            {data:'created_at'}
        ]
    });
    $('#filterStatus').on('change', function () { table.ajax.reload(); });
});
</script>
@stop

