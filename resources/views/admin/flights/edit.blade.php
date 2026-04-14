@extends('adminlte::page')

@section('title', 'Edit Flight')

@section('content')

<div class="card">


{{-- HEADER --}}
<div class="card-header bg-primary d-flex align-items-center">
    <h5 class="mb-0 text-white">
        <i class="fas fa-plane"></i> Edit Flight
    </h5>
</div>

<form method="POST" action="{{ route('admin.flights.update',$flight->id) }}">
    @csrf
    @method('PUT')

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

        @php $price = $flight->flightPrices->first(); @endphp

        {{-- ROW 1 --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Airline Name</label>
                <input type="text" name="airline_name"
                       value="{{ old('airline_name', $flight->airline_name) }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Flight Number</label>
                <input type="text" name="flight_number"
                       value="{{ old('flight_number', $flight->flight_number) }}"
                       class="form-control">
            </div>
        </div>

        {{-- ROW 2 --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Departure City</label>
                <input type="text" name="departure_city"
                       value="{{ old('departure_city', $flight->departure_city) }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Arrival City</label>
                <input type="text" name="arrival_city"
                       value="{{ old('arrival_city', $flight->arrival_city) }}"
                       class="form-control">
            </div>
        </div>

        {{-- NEW ROW: DATES --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Departure Date</label>
                <input type="date"
                       name="departure_date"
                       value="{{ old('departure_date', $flight->departure_date) }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Return Date</label>
                <input type="date"
                       name="return_date"
                       value="{{ old('return_date', $flight->return_date) }}"
                       class="form-control">
            </div>
        </div>

        {{-- ROW 3 --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Departure Time</label>
                <input type="datetime-local"
                       name="departure_time"
                       value="{{ \Carbon\Carbon::parse($flight->departure_time)->format('Y-m-d\TH:i') }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Arrival Time</label>
                <input type="datetime-local"
                       name="arrival_time"
                       value="{{ \Carbon\Carbon::parse($flight->arrival_time)->format('Y-m-d\TH:i') }}"
                       class="form-control">
            </div>
        </div>

        {{-- NEW ROW: BAGGAGE --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Baggage (KG per passenger)</label>
                <input type="number"
                       name="baggage_kg"
                       value="{{ old('baggage_kg', $flight->baggage_kg) }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $flight->category_id) == $cat->id ? 'selected' : '' }}>
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
                    <option value="economy" {{ old('seat_class', optional($price)->seat_class) == 'economy' ? 'selected' : '' }}>
                        Economy
                    </option>
                    <option value="business" {{ old('seat_class', optional($price)->seat_class) == 'business' ? 'selected' : '' }}>
                        Business
                    </option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Price</label>
                <input type="number"
                       name="price"
                       value="{{ old('price', optional($price)->price) }}"
                       class="form-control">
            </div>
        </div>

        {{-- PRICE DATES --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Valid From</label>
                <input type="date"
                       name="valid_from"
                       value="{{ old('valid_from', optional($price)->valid_from) }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Valid To</label>
                <input type="date"
                       name="valid_to"
                       value="{{ old('valid_to', optional($price)->valid_to) }}"
                       class="form-control">
            </div>
        </div>

        {{-- STATUS --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ old('status', optional($price)->status) == 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ old('status', optional($price)->status) == 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="card-footer text-right">
        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Update Flight
        </button>
    </div>

</form>


</div>

@stop
