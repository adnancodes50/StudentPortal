@extends('adminlte::page')

@section('title', 'Hotels')

@section('content_header')
    <h1>Hotels Management 🏨</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
            + Add Hotel
        </button>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Rooms</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotels as $hotel)
                <tr>
                    <td>{{ $hotel->id }}</td>
                    <td>{{ $hotel->name }}</td>
                    <td>{{ $hotel->city }}</td>
                    <td>
                        <span class="badge bg-info">{{ $hotel->rooms->count() }}</span>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-info" href="{{ route('admin.hotels.show', $hotel->id) }}">View</a>
                        <button class="btn btn-sm btn-warning editBtn"
                            data-id="{{ $hotel->id }}"
                            data-name="{{ $hotel->name }}"
                            data-city="{{ $hotel->city }}">
                            Edit
                        </button>
                        <form action="{{ route('admin.hotels.delete', $hotel->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $hotels->links() }}
    </div>
</div>

{{-- ==================== ADD MODAL ==================== --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.hotels.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Hotel</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hotel Name *</label>
                        <input type="text" name="name" class="form-control" required maxlength="150" placeholder="e.g., Grand Plaza">
                    </div>
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" class="form-control" required maxlength="100" placeholder="e.g., New York">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ==================== EDIT MODAL ==================== --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Hotel</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hotel Name *</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required maxlength="150">
                    </div>
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" id="edit_city" class="form-control" required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Edit button click
        $('.editBtn').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let city = $(this).data('city');

            // Set form action URL
            let updateUrl = "{{ route('admin.hotels.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', id);
            $('#editForm').attr('action', updateUrl);

            // Fill fields
            $('#edit_name').val(name);
            $('#edit_city').val(city);

            // Show modal
            $('#editModal').modal('show');
        });

        // Reset edit modal when closed
        $('#editModal').on('hidden.bs.modal', function() {
            $('#editForm')[0].reset();
        });
    });
</script>
@stop