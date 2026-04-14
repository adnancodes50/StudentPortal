@extends('adminlte::page')

@section('title', 'Add Flight')

@section('content')

<div class="card">


{{-- HEADER --}}
<div class="card-header bg-primary d-flex justify-content-between align-items-center">
    <h5 class="mb-0 text-white">
        <i class="fas fa-plane"></i> Add Flight
    </h5>
</div>

{{-- FORM --}}
<form method="POST" action="{{ route('admin.flights.store') }}">
@csrf

<div class="card-body">

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ROW 1 --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Airline Name</label>
            <input type="text" name="airline_name" class="form-control" value="{{ old('airline_name') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Flight Number</label>
            <input type="text" name="flight_number" class="form-control" value="{{ old('flight_number') }}">
        </div>
    </div>

    {{-- ROW 2 --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Departure City</label>
            <input type="text" name="departure_city" class="form-control" value="{{ old('departure_city') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Arrival City</label>
            <input type="text" name="arrival_city" class="form-control" value="{{ old('arrival_city') }}">
        </div>
    </div>

    {{-- ROW 3 --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Departure Date</label>
            <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Return Date</label>
            <input type="date" name="return_date" class="form-control" value="{{ old('return_date') }}">
        </div>
    </div>

    {{-- ROW 4 --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Departure Time</label>
            <input type="datetime-local" name="departure_time" class="form-control" value="{{ old('departure_time') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Arrival Time</label>
            <input type="datetime-local" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}">
        </div>
    </div>

    {{-- ROW 5 --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Baggage (KG per passenger)</label>
            <input type="number" name="baggage_kg" class="form-control" value="{{ old('baggage_kg', 23) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control">
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ ucfirst($cat->type) }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <hr>

    <h5 class="mb-3">Flight Price</h5>

    {{-- PRICE --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Seat Class</label>
            <select name="seat_class" class="form-control">
                <option value="economy">Economy</option>
                <option value="business">Business</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}">
        </div>
    </div>

    {{-- PRICE VALIDITY --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Valid From</label>
            <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Valid To</label>
            <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to') }}">
        </div>
    </div>

    {{-- STATUS --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

</div>

{{-- FOOTER --}}
<div class="card-footer text-right">
    <button class="btn btn-success">
        <i class="fas fa-save"></i> Save Flight
    </button>
</div>

</form>


</div>

@stop
