@extends('adminlte::page')

@section('title', 'Flight Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Flight Details</h1>
        <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-primary text-white"><b>Flight Information</b></div>
                <div class="card-body">
                    <p class="mb-2"><b>Airline:</b> {{ $flight->airline_name }}</p>
                    <p class="mb-2"><b>Flight #:</b> {{ $flight->flight_number }}</p>
                    <p class="mb-2"><b>Route:</b> {{ $flight->departure_city }} -> {{ $flight->arrival_city }}</p>
                    <p class="mb-2"><b>Airport Codes:</b> {{ $flight->origin_airport_code ?: '-' }} -> {{ $flight->destination_airport_code ?: '-' }}</p>
                    <p class="mb-2"><b>Departure:</b> {{ $flight->departure_time }}</p>
                    <p class="mb-2"><b>Arrival:</b> {{ $flight->arrival_time }}</p>
                    <p class="mb-2"><b>Duration:</b> {{ $flight->duration_minutes ?: '-' }} min</p>
                    <p class="mb-2"><b>Baggage:</b> {{ $flight->baggage_kg }} KG</p>
                    <p class="mb-0">
                        <b>Status:</b>
                        @php $status = strtolower($flight->status ?? 'scheduled'); @endphp
                        <span class="badge {{ $status === 'scheduled' ? 'bg-success' : ($status === 'delayed' ? 'bg-warning' : 'bg-danger') }}">
                            {{ ucfirst($status) }}
                        </span>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.flights.edit', $flight->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i>Edit Flight
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0"><b>Price Details</b></h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addPriceModal">
                            <i class="fas fa-plus mr-1"></i>Add Price
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="pricesTable" class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Class</th>
                                <th>Price</th>
                                <th>Validity</th>
                                <th>Status</th>
                                <th>Refundable</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($flight->flightPrices as $price)
                                <tr>
                                    <td>{{ $price->id }}</td>
                                    <td>{{ ucfirst($price->seat_class) }}</td>
                                    <td>{{ $price->currency ?? 'PKR' }} {{ number_format($price->price, 2) }}</td>
                                    <td>{{ $price->valid_from }} to {{ $price->valid_to }}</td>
                                    <td>
                                        <span class="badge {{ $price->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($price->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $price->is_refundable ? 'Yes' : 'No' }}</td>
                                    <td class="text-nowrap">
                                        <button
                                            class="btn btn-sm btn-warning editPriceBtn"
                                            data-id="{{ $price->id }}"
                                            data-seat_class="{{ $price->seat_class }}"
                                            data-price="{{ $price->price }}"
                                            data-currency="{{ $price->currency ?? 'PKR' }}"
                                            data-valid_from="{{ $price->valid_from }}"
                                            data-valid_to="{{ $price->valid_to }}"
                                            data-status="{{ $price->status }}"
                                            data-is_refundable="{{ $price->is_refundable ? 1 : 0 }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.flights.prices.delete', [$flight->id, $price->id]) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this price?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No prices added yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPriceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable price-modal">
            <form method="POST" class="w-100" action="{{ route('admin.flights.prices.store', $flight->id) }}">
                @csrf
                <div class="modal-content w-100">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Price</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Seat Class *</label><select name="seat_class" class="form-control" required><option value="economy">Economy</option><option value="business">Business</option><option value="first">First</option></select></div>
                        <div class="form-group"><label>Price *</label><input type="number" step="0.01" name="price" class="form-control" required min="0"></div>
                        <div class="form-group"><label>Currency</label><input type="text" name="currency" class="form-control" value="PKR" maxlength="10"></div>
                        <div class="form-group"><label>Valid From *</label><input type="date" name="valid_from" class="form-control" required></div>
                        <div class="form-group"><label>Valid To *</label><input type="date" name="valid_to" class="form-control" required></div>
                        <div class="form-group"><label>Status *</label><select name="status" class="form-control" required><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        <div class="form-check"><input type="checkbox" name="is_refundable" value="1" class="form-check-input" id="add_is_refundable"><label class="form-check-label" for="add_is_refundable">Refundable</label></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Price</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editPriceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable price-modal">
            <form method="POST" id="editPriceForm" class="w-100">
                @csrf
                @method('PUT')
                <div class="modal-content w-100">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Price</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Seat Class *</label><select name="seat_class" id="edit_seat_class" class="form-control" required><option value="economy">Economy</option><option value="business">Business</option><option value="first">First</option></select></div>
                        <div class="form-group"><label>Price *</label><input type="number" step="0.01" name="price" id="edit_price" class="form-control" required min="0"></div>
                        <div class="form-group"><label>Currency</label><input type="text" name="currency" id="edit_currency" class="form-control" maxlength="10"></div>
                        <div class="form-group"><label>Valid From *</label><input type="date" name="valid_from" id="edit_valid_from" class="form-control" required></div>
                        <div class="form-group"><label>Valid To *</label><input type="date" name="valid_to" id="edit_valid_to" class="form-control" required></div>
                        <div class="form-group"><label>Status *</label><select name="status" id="edit_status" class="form-control" required><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        <div class="form-check"><input type="checkbox" name="is_refundable" value="1" class="form-check-input" id="edit_is_refundable"><label class="form-check-label" for="edit_is_refundable">Refundable</label></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Price</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
<style>
    #addPriceModal .price-modal,
    #editPriceModal .price-modal {
        max-width: 640px !important;
        width: 88% !important;
    }

    #addPriceModal .modal-header,
    #editPriceModal .modal-header {
        padding: 0.75rem 1rem;
    }

    #addPriceModal .modal-body,
    #editPriceModal .modal-body {
        padding: 1rem;
    }

    @media (max-width: 767.98px) {
        #addPriceModal .price-modal,
        #editPriceModal .price-modal {
            width: calc(100% - 1rem) !important;
            max-width: calc(100% - 1rem) !important;
            margin: 0.5rem auto;
        }
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#pricesTable').DataTable();

    $(document).on('click', '.editPriceBtn', function () {
        const btn = $(this);
        let updateUrl = "{{ route('admin.flights.prices.update', [$flight->id, ':priceId']) }}";
        updateUrl = updateUrl.replace(':priceId', btn.data('id'));
        $('#editPriceForm').attr('action', updateUrl);
        $('#edit_seat_class').val(btn.data('seat_class'));
        $('#edit_price').val(btn.data('price'));
        $('#edit_currency').val(btn.data('currency'));
        $('#edit_valid_from').val(btn.data('valid_from'));
        $('#edit_valid_to').val(btn.data('valid_to'));
        $('#edit_status').val(btn.data('status'));
        $('#edit_is_refundable').prop('checked', Number(btn.data('is_refundable')) === 1);
        $('#editPriceModal').modal('show');
    });
});
</script>
@stop

