@extends('adminlte::page')

@section('title', 'Umrah Packages')

@section('content_header')
    <h1>Umrah Packages</h1>
@stop

@section('content')
    <div class="card mb-3"><div class="card-header"><b>Create Package</b></div><div class="card-body">
        <form method="POST" action="{{ route('admin.umrah.packages.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-2"><input class="form-control" name="package_name" placeholder="Package name" required></div>
                <div class="col-md-2 mb-2"><input class="form-control" name="total_days" type="number" min="1" placeholder="Days" required></div>
                <div class="col-md-2 mb-2"><input class="form-control" name="price_per_person" type="number" step="0.01" min="0" placeholder="Price" required></div>
                <div class="col-md-2 mb-2"><input class="form-control" name="group_size" type="number" min="1" placeholder="Group size"></div>
                <div class="col-md-2 mb-2"><button class="btn btn-primary btn-block">Create</button></div>
            </div>
        </form>
    </div></div>

    <div class="card"><div class="card-body">
        <table id="packagesTable" class="table table-bordered table-striped">
            <thead><tr><th>ID</th><th>Name</th><th>Days</th><th>Price</th><th>Makkah</th><th>Madinah</th><th>Group</th><th>Depart</th><th>Return</th></tr></thead>
        </table>
    </div></div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#packagesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.umrah.packages.data') }}" },
        columns: [{data:'id'},{data:'package_name'},{data:'total_days'},{data:'price_per_person'},{data:'makkah_hotel'},{data:'madinah_hotel'},{data:'group_size'},{data:'departure_date'},{data:'return_date'}]
    });
});
</script>
@stop

