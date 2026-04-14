@extends('adminlte::page')

@section('title', 'Banks')

@section('content')

<div class="card">


{{-- HEADER --}}
<div class="card-header bg-primary d-flex align-items-center">
    <h5 class="mb-0 text-white">
        <i class="fas fa-university"></i> Banks Management
    </h5>

    <button class="btn btn-info btn-sm ml-auto"
            data-toggle="modal"
            data-target="#bankModal">
        <i class="fas fa-plus"></i> Add Bank
    </button>
</div>

{{-- BODY --}}
<div class="card-body">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead style="background:#f1f1f1;">
            <tr>
                <th>#</th>
                <th>Bank Name</th>
                <th>Account Title</th>
                <th>Account Number</th>
                <th width="140">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($banks as $index => $bank)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $bank->bank_name }}</td>
                <td>{{ $bank->account_title }}</td>
                <td><strong>{{ $bank->account_number }}</strong></td>

                <td>
                    {{-- EDIT --}}
                    <button type="button"
                        class="btn btn-sm btn-warning editBtn"
                        data-id="{{ $bank->id }}"
                        data-name="{{ $bank->bank_name }}"
                        data-title="{{ $bank->account_title }}"
                        data-number="{{ $bank->account_number }}">
                        <i class="fas fa-edit"></i>
                    </button>

                    {{-- DELETE --}}
                    <form action="{{ route('admin.banks.delete',$bank->id) }}"
                          method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this bank?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" class="text-center">No banks found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

</div>


</div>

{{-- ================= ADD MODAL ================= --}}

<div class="modal fade" id="bankModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.banks.store') }}">
            @csrf


        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="text-white">Add Bank</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="text" name="bank_name" class="form-control mb-2" placeholder="Bank Name">
                <input type="text" name="account_title" class="form-control mb-2" placeholder="Account Title">
                <input type="text" name="account_number" class="form-control" placeholder="Account Number">
            </div>

            <div class="modal-footer">
                <button class="btn btn-success">Save</button>
            </div>
        </div>

    </form>
</div>


</div>

{{-- ================= EDIT MODAL ================= --}}

<div class="modal fade" id="editBankModal">
    <div class="modal-dialog">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')


        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5>Edit Bank</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="text" name="bank_name" id="edit_bank_name" class="form-control mb-2">
                <input type="text" name="account_title" id="edit_account_title" class="form-control mb-2">
                <input type="text" name="account_number" id="edit_account_number" class="form-control">
            </div>

            <div class="modal-footer">
                <button class="btn btn-warning">Update</button>
            </div>

        </div>

    </form>
</div>


</div>

{{-- JS --}}

<script>
$(document).on('click', '.editBtn', function(){

    let id = $(this).data('id');
    let name = $(this).data('name');
    let title = $(this).data('title');
    let number = $(this).data('number');

    let url = "{{ route('admin.banks.update', ':id') }}";
    url = url.replace(':id', id);

    $('#editForm').attr('action', url);

    $('#edit_bank_name').val(name);
    $('#edit_account_title').val(title);
    $('#edit_account_number').val(number);

    $('#editBankModal').modal('show');
});
</script>

@stop
