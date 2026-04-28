@extends('adminlte::page')

@section('title', 'Hotel Rooms')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $hotel->name }} - Rooms</h1>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <b>Hotel Details</b>
                </div>
                <div class="card-body">
                    <p class="mb-2"><b>Hotel:</b> {{ $hotel->name }}</p>
                    <p class="mb-2"><b>City:</b> {{ $hotel->city }}</p>
                    <p class="mb-0">
                        <b>Total Rooms:</b>
                        <span id="totalRoomsBadge" class="badge bg-info">{{ $hotel->rooms_count }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0"><b>Assigned Rooms</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRoomModal">Add Room</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="roomsTable" class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Room Name</th>
                                <th>Room Type</th>
                                <th>Beds</th>
                                <th>Availability</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog add-room-modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 680px; width: 90%;">
            <form id="addRoomForm" class="w-100" method="POST" action="{{ route('admin.hotels.rooms.store', $hotel->id) }}">
                @csrf
                <div class="modal-content w-100">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Room</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Room Name *</label>
                            <input type="text" name="room_name" class="form-control" required maxlength="100" placeholder="e.g., Deluxe 101">
                        </div>
                        <div class="form-group mb-3">
                            <label>Room Type *</label>
                            <select name="room_type" class="form-control" required>
                                <option value="">Select room type</option>
                                <option value="single">Single</option>
                                <option value="double">Double</option>
                                <option value="triple">Triple</option>
                                <option value="quad">Quad</option>
                                <option value="family">Family</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Bed Capacity *</label>
                            <select name="bed_capacity" class="form-control" required>
                                <option value="">Select beds</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $i === 1 ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Availability *</label>
                            <select name="availability" class="form-control" required>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="saveRoomBtn" type="submit" class="btn btn-primary">Save Room</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
<style>
    #addRoomModal {
        z-index: 1065;
    }

    #addRoomModal .add-room-modal-dialog { max-width: 680px !important; width: 90% !important; }

    #addRoomModal .modal-content {
        border-radius: 8px;
    }

    #addRoomModal .modal-body {
        padding: 1rem 1.25rem;
    }

    #addRoomModal .modal-footer {
        justify-content: flex-end;
        gap: 8px;
    }

    #addRoomModal .form-control {
        height: calc(2.25rem + 2px);
    }

    #addRoomModal .close {
        opacity: 1;
    }

    #roomsTable_wrapper .dataTables_length,
    #roomsTable_wrapper .dataTables_filter {
        margin-bottom: 0.5rem;
    }

    #roomsTable_wrapper .dataTables_filter {
        text-align: right;
    }

    #roomsTable_wrapper .dataTables_filter label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0;
    }

    #roomsTable_wrapper .dataTables_filter input {
        margin-left: 0 !important;
        width: 190px;
    }

    #roomsTable_wrapper .dataTables_paginate {
        text-align: right;
        float: none;
    }

    @media (max-width: 767.98px) {
        #roomsTable_wrapper .dataTables_length,
        #roomsTable_wrapper .dataTables_filter,
        #roomsTable_wrapper .dataTables_info,
        #roomsTable_wrapper .dataTables_paginate {
            text-align: left !important;
        }

        #roomsTable_wrapper .dataTables_filter label {
            width: 100%;
            justify-content: flex-start;
        }

        #roomsTable_wrapper .dataTables_filter input {
            width: 100%;
        }

        #addRoomModal .add-room-modal-dialog {
            max-width: calc(100% - 1rem);
            margin: 0.5rem auto;
            width: calc(100% - 1rem);
        }
    }
</style>
@stop

@section('js')
<script>
    $(function () {
        const roomsDataUrl = @json(route('admin.hotels.rooms.data', $hotel->id));
        const storeRoomUrl = @json(route('admin.hotels.rooms.store', $hotel->id));
        const deleteRoomTemplate = @json(route('admin.hotels.rooms.delete', ':id'));

        const table = $('#roomsTable').DataTable({
            processing: true,
            ajax: roomsDataUrl,
            autoWidth: false,
            dom: "<'row align-items-center mb-2'<'col-sm-6'l><'col-sm-6 text-sm-right'f>>" +
                 "rt" +
                 "<'row align-items-center mt-2'<'col-sm-5'i><'col-sm-7 text-sm-right'p>>",
            columns: [
                { data: 'id' },
                { data: 'room_name' },
                { data: 'room_type' },
                { data: 'bed_capacity' },
                {
                    data: 'availability',
                    render: function (value) {
                        if ((value || 'available') === 'available') {
                            return '<span class="badge bg-success">Available</span>';
                        }
                        return '<span class="badge bg-danger">Unavailable</span>';
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (id) {
                        return '<button class="btn btn-sm btn-danger deleteRoomBtn" data-id="' + id + '">Delete</button>';
                    }
                }
            ],
            language: {
                emptyTable: 'No rooms assigned.'
            }
        });

        $('#addRoomForm').on('submit', function (e) {
            e.preventDefault();
            const $form = $(this);
            const $btn = $('#saveRoomBtn');

            $.ajax({
                url: storeRoomUrl,
                method: 'POST',
                data: $form.serialize(),
                beforeSend: function () {
                    $btn.prop('disabled', true).text('Saving...');
                },
                success: function (res) {
                    $('#addRoomModal').modal('hide');
                    $form[0].reset();
                    table.ajax.reload(null, false);
                    if (typeof res.total_rooms !== 'undefined') {
                        $('#totalRoomsBadge').text(res.total_rooms);
                    }
                },
                error: function () {
                    alert('Failed to add room. Please check input and try again.');
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Save Room');
                }
            });
        });

        $(document).on('click', '.deleteRoomBtn', function () {
            const roomId = $(this).data('id');
            if (!confirm('Delete room?')) {
                return;
            }

            const deleteUrl = deleteRoomTemplate.replace(':id', roomId);
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function (res) {
                    table.ajax.reload(null, false);
                    if (typeof res.total_rooms !== 'undefined') {
                        $('#totalRoomsBadge').text(res.total_rooms);
                    }
                },
                error: function () {
                    alert('Failed to delete room.');
                }
            });
        });
    });
</script>
@stop

