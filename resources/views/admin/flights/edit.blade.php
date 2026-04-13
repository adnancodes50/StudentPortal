@extends('adminlte::page')

@section('title', 'Edit Flight')

@section('content')

<div class="card">

    {{-- HEADER --}}
    <div class="card-header bg-primary d-flex align-items-center"
         {{-- style="background-color:#9aa30f; color:white;" --}}
         >

        <h5 class="mb-0">
            <i class="fas fa-plane"></i> Edit Flight
        </h5>

        {{-- <a href="{{ route('admin.flights.index') }}"
           class="btn btn-light btn-sm ms-auto">
            <i class="fas fa-arrow-left"></i> Back
        </a> --}}

    </div>

    <form method="POST" action="{{ route('admin.flights.update',$flight->id) }}">
    @csrf

    <div class="card-body">

        {{-- PRICE SAFE --}}
        @php $price = $flight->flightPrices->first(); @endphp

        {{-- ROW 1 --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Airline Name</label>
                <input type="text" name="airline_name"
                       value="{{ $flight->airline_name }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Flight Number</label>
                <input type="text" name="flight_number"
                       value="{{ $flight->flight_number }}"
                       class="form-control">
            </div>
        </div>

        {{-- ROW 2 --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Departure City</label>
                <input type="text" name="departure_city"
                       value="{{ $flight->departure_city }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Arrival City</label>
                <input type="text" name="arrival_city"
                       value="{{ $flight->arrival_city }}"
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

        {{-- ROW 4 --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ $flight->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ ucfirst($cat->type) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr>

        <h5 class="mb-3">Flight Price</h5>

        {{-- PRICE ROW --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Seat Class</label>
                <select name="seat_class" class="form-control">
                    <option value="economy" {{ optional($price)->seat_class == 'economy' ? 'selected' : '' }}>
                        Economy
                    </option>
                    <option value="business" {{ optional($price)->seat_class == 'business' ? 'selected' : '' }}>
                        Business
                    </option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Price</label>
                <input type="number"
                       name="price"
                       value="{{ optional($price)->price }}"
                       class="form-control">
            </div>
        </div>

        {{-- DATE ROW --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Valid From</label>
                <input type="date"
                       name="valid_from"
                       value="{{ optional($price)->valid_from }}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Valid To</label>
                <input type="date"
                       name="valid_to"
                       value="{{ optional($price)->valid_to }}"
                       class="form-control">
            </div>
        </div>

        {{-- STATUS --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ optional($price)->status == 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ optional($price)->status == 'inactive' ? 'selected' : '' }}>
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