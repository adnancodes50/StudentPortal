@extends('adminlte::page')

@section('title', 'Wallet / Ledger')

@section('content_header')
    <h1>Wallet / Ledger</h1>
@stop

@section('content')
    <div class="card"><div class="card-body">
        <table id="walletTable" class="table table-bordered table-striped">
            <thead><tr><th>ID</th><th>Agent/Booking</th><th>Type</th><th>Amount</th><th>Balance After</th><th>Reference</th><th>Created</th></tr></thead>
        </table>
    </div></div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#walletTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.wallet.data') }}" },
        columns: [
            {data:'id'},
            {data:null,render:function(row){return row.agent_id?`Agent #${row.agent_id}`:(row.reference_id?`Booking #${row.reference_id}`:'-');}},
            {data:'type'},{data:'amount'},{data:'balance_after'},
            {data:null,render:function(row){return row.reference_type?`${row.reference_type} ${row.reference_id??''}`:'-';}},
            {data:'created_at'}
        ]
    });
});
</script>
@stop

