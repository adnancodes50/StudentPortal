@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')
    <h1>Categories Management 📂</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
            + Add Category
        </button>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>          {{-- Image column --}}
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    {{-- Image cell --}}
                    <td>
                        @if($cat->image)
                            <img src="{{ Storage::url($cat->image) }}" width="50" height="50" style="object-fit: cover; border-radius: 4px;">
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>
                    <td>{{ $cat->name }}</td>
                    <td>{{ ucfirst($cat->type) }}</td>
                    <td>
                        @if($cat->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning editBtn"
                            data-id="{{ $cat->id }}"
                            data-name="{{ $cat->name }}"
                            data-type="{{ $cat->type }}"
                            data-status="{{ $cat->status }}"
                            data-description="{{ $cat->description }}"
                            data-image="{{ $cat->image }}">
                            Edit
                        </button>

                        <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ==================== ADD MODAL ==================== --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="flight">Flight</option>
                            <option value="umrah">Umrah</option>
                            <option value="tour">Tour</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Image (JPEG, PNG, JPG, GIF, max 2MB)</label>
                        <input type="file" name="image" class="form-control-file" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" id="edit_type" class="form-control" required>
                            <option value="flight">Flight</option>
                            <option value="umrah">Umrah</option>
                            <option value="tour">Tour</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group" id="currentImageContainer">
                        {{-- Current image preview will be inserted here via JS --}}
                    </div>
                    <div class="form-group">
                        <label>Change Image (optional)</label>
                        <input type="file" name="image" class="form-control-file" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
        $('.editBtn').click(function () {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let type = $(this).data('type');
            let status = $(this).data('status');
            let description = $(this).data('description');
            let image = $(this).data('image');

            // Set form action URL
            let updateUrl = "{{ route('admin.categories.update', ':id') }}";
updateUrl = updateUrl.replace(':id', id);
$('#editForm').attr('action', updateUrl);

            // Fill fields
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_type').val(type);
            $('#edit_status').val(status);
            $('#edit_description').val(description);

            // Show current image preview
            let container = $('#currentImageContainer');
            container.empty();
            if (image) {
                let imageUrl = "{{ Storage::url('') }}" + image;
                let previewHtml = `
                    <label>Current Image</label>
                    <div>
                        <img src="${imageUrl}" width="100" style="border:1px solid #ddd; padding:3px; border-radius:4px;">
                    </div>
                `;
                container.html(previewHtml);
            } else {
                container.html('<small class="text-muted">No current image</small>');
            }

            $('#editModal').modal('show');
        });

        // Reset edit modal when closed
        $('#editModal').on('hidden.bs.modal', function () {
            $('#editForm')[0].reset();
            $('#currentImageContainer').empty();
        });
    });
</script>
@stop