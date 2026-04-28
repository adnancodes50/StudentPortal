@extends('adminlte::page')

@section('title', 'Reports & Analysis')

@section('content_header')
    <h1>Reports & Analysis - Agents</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="agentReportTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('css')
<style>
    #agentReportTable td:last-child,
    #agentReportTable th:last-child {
        text-align: center;
        white-space: nowrap;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#agentReportTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.reports.agents.data') }}" },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'status', render: function (value) {
                const isActive = String(value).toLowerCase() === 'active';
                const cls = isActive ? 'badge bg-success' : 'badge bg-danger';
                return `<span class="${cls}">${isActive ? 'Active' : 'Inactive'}</span>`;
            }},
            { data: 'created_at' },
            { data: null, orderable: false, searchable: false, render: function (row) {
                return `
                    <a class="btn btn-sm btn-info" href="/admin/reports/agents/${row.id}" title="Open report">
                        <i class="fas fa-chart-line"></i>
                    </a>
                `;
            }},
        ]
    });
});
</script>
@stop

