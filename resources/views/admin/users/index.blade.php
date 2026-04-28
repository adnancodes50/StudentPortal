@extends('adminlte::page')

@section('title', 'Users & Agents')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Users & Agents</h1>
        <button class="btn btn-primary btn-sm" id="btnCreate">Create User/Agent</button>
    </div>
@stop

@section('content')
    <div class="card"><div class="card-body">
        <table id="usersTable" class="table table-bordered table-striped">
            <thead><tr><th>ID</th><th>Role</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
        </table>
    </div></div>

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form id="createForm">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Create User / Agent</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2"><label>Role</label><select class="form-control" name="role" required><option value="user">User</option><option value="agent">Agent</option></select></div>
                        <div class="form-group mb-2"><label>Name</label><input class="form-control" name="name" required></div>
                        <div class="form-group mb-2"><label>Email</label><input class="form-control" type="email" name="email" required></div>
                        <div class="form-group mb-2"><label>Password</label><input class="form-control" type="password" name="password" required></div>
                        <div class="form-group mb-2"><label>Phone</label><input class="form-control" name="phone"></div>
                        <div class="form-group mb-2"><label>Status</label><select class="form-control" name="status" required><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        <div class="form-group mb-2"><label>Passport No</label><input class="form-control" name="passport_no"></div>
                        <div class="form-group mb-2"><label>Address</label><input class="form-control" name="address"></div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-primary">Create</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="editId">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit User / Agent</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2"><label>Role</label><select class="form-control" name="role" required><option value="user">User</option><option value="agent">Agent</option></select></div>
                        <div class="form-group mb-2"><label>Name</label><input class="form-control" name="name" required></div>
                        <div class="form-group mb-2"><label>Email</label><input class="form-control" type="email" name="email" required></div>
                        <div class="form-group mb-2"><label>Password (optional)</label><input class="form-control" type="password" name="password"></div>
                        <div class="form-group mb-2"><label>Phone</label><input class="form-control" name="phone"></div>
                        <div class="form-group mb-2"><label>Status</label><select class="form-control" name="status" required><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        <div class="form-group mb-2"><label>Passport No</label><input class="form-control" name="passport_no"></div>
                        <div class="form-group mb-2"><label>Address</label><input class="form-control" name="address"></div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-primary">Update</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Profile Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="profileBody"></div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    const table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.users.data') }}" },
        columns: [
            { data: 'id' }, { data: 'role' }, { data: 'name' }, { data: 'email' }, { data: 'phone' },
            { data: 'status', render: function (value) {
                const isActive = String(value).toLowerCase() === 'active';
                const cls = isActive ? 'badge bg-success' : 'badge bg-danger';
                const label = isActive ? 'Active' : 'Inactive';
                return `<span class="${cls}">${label}</span>`;
            }}, { data: 'created_at' },
            { data: null, orderable: false, searchable: false, render: function (row) {
                const viewBtn = `<button class="btn btn-sm btn-info viewBtn" data-id="${row.id}">View</button>`;
                const editBtn = `<button class="btn btn-sm btn-warning editBtn ml-1" data-id="${row.id}">Edit</button>`;
                const delBtn = `<button class="btn btn-sm btn-danger deleteBtn ml-1" data-id="${row.id}">Delete</button>`;
                return viewBtn + editBtn + delBtn;
            }}
        ]
    });

    $('#btnCreate').on('click', function () {
        $('#createModal').modal('show');
    });

    $('#createForm').on('submit', function (e) {
        e.preventDefault();
        $.post("{{ route('admin.users.store') }}", $(this).serialize())
            .done(function () { $('#createModal').modal('hide'); $('#createForm')[0].reset(); table.ajax.reload(null, false); });
    });

    $(document).on('click', '.viewBtn', function () {
        const id = $(this).data('id');
        $.get(`/admin/users/${id}`).done(function (row) {
            const html = `
                <p><b>Role:</b> ${row.role ?? '-'}</p>
                <p><b>Name:</b> ${row.name ?? '-'}</p>
                <p><b>Email:</b> ${row.email ?? '-'}</p>
                <p><b>Phone:</b> ${row.phone ?? '-'}</p>
                <p><b>Passport No:</b> ${row.passport_no ?? '-'}</p>
                <p><b>Address:</b> ${row.address ?? '-'}</p>
                <p><b>Status:</b> ${row.status ?? '-'}</p>
            `;
            $('#profileBody').html(html);
            $('#viewModal').modal('show');
        });
    });

    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        $.get(`/admin/users/${id}`).done(function (row) {
            $('#editId').val(row.id);
            $('#editForm [name="role"]').val(row.role);
            $('#editForm [name="name"]').val(row.name);
            $('#editForm [name="email"]').val(row.email);
            $('#editForm [name="phone"]').val(row.phone);
            $('#editForm [name="status"]').val(row.status);
            $('#editForm [name="passport_no"]').val(row.passport_no);
            $('#editForm [name="address"]').val(row.address);
            $('#editModal').modal('show');
        });
    });

    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#editId').val();
        const payload = $(this).serialize() + '&_method=PUT';
        $.post(`/admin/users/${id}`, payload).done(function () {
            $('#editModal').modal('hide');
            table.ajax.reload(null, false);
        });
    });

    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Delete this record?')) return;
        $.post(`/admin/users/${id}`, {_token: '{{ csrf_token() }}', _method: 'DELETE'})
            .done(function () { table.ajax.reload(null, false); });
    });
});
</script>
@stop

