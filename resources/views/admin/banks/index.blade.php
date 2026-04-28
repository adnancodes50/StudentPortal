@extends('adminlte::page')

@section('title', 'Banks')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Banks</h1>
        <button class="btn btn-primary btn-sm" id="btnCreateBank">Create Bank</button>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="banksTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Bank Name</th>
                    <th>Account Title</th>
                    <th>Account Number</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createBankModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form id="createBankForm" method="POST" action="{{ route('admin.banks.store') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Create Bank</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="bank_name" class="form-control mb-2" placeholder="Bank Name" required>
                        <input type="text" name="account_title" class="form-control mb-2" placeholder="Account Title" required>
                        <input type="text" name="account_number" class="form-control" placeholder="Account Number" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBankModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form id="editBankForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Bank</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="bank_name" id="edit_bank_name" class="form-control mb-2" required>
                        <input type="text" name="account_title" id="edit_account_title" class="form-control mb-2" required>
                        <input type="text" name="account_number" id="edit_account_number" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    const table = $('#banksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.banks.data') }}" },
        columns: [
            { data: 'id' },
            { data: 'bank_name' },
            { data: 'account_title' },
            { data: 'account_number' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (row) {
                    const editBtn = `<button class="btn btn-sm btn-warning editBtn" data-id="${row.id}" data-name="${row.bank_name}" data-title="${row.account_title}" data-number="${row.account_number}">Edit</button>`;
                    const delBtn = `<button class="btn btn-sm btn-danger ml-1 deleteBtn" data-id="${row.id}">Delete</button>`;
                    return editBtn + delBtn;
                }
            }
        ]
    });

    $('#btnCreateBank').on('click', function () {
        $('#createBankModal').modal('show');
    });

    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const title = $(this).data('title');
        const number = $(this).data('number');
        let url = "{{ route('admin.banks.update', ':id') }}";
        url = url.replace(':id', id);
        $('#editBankForm').attr('action', url);
        $('#edit_bank_name').val(name);
        $('#edit_account_title').val(title);
        $('#edit_account_number').val(number);
        $('#editBankModal').modal('show');
    });

    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Delete this bank?')) return;
        $.post(`/admin/banks/delete/${id}`, {_token: '{{ csrf_token() }}', _method: 'DELETE'})
            .done(function () { table.ajax.reload(null, false); });
    });
});
</script>
@stop
