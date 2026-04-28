@extends('adminlte::page')

@section('title', 'Add Flight')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Create Flight</h1>
        <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Back
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Create Flight</h3>
            {{-- <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a> --}}
        </div>
        <form method="POST" action="{{ route('admin.flights.store') }}">
            @csrf
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Airline Name *</label>
                        <input type="text" name="airline_name" class="form-control" value="{{ old('airline_name') }}" required maxlength="100">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Flight Number *</label>
                        <input type="text" name="flight_number" class="form-control" value="{{ old('flight_number') }}" required maxlength="50">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Departure City *</label>
                        <input type="text" name="departure_city" class="form-control" value="{{ old('departure_city') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Arrival City *</label>
                        <input type="text" name="arrival_city" class="form-control" value="{{ old('arrival_city') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Origin Airport Code</label>
                        <input type="text" name="origin_airport_code" class="form-control" value="{{ old('origin_airport_code') }}" placeholder="e.g. LHE">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Destination Airport Code</label>
                        <input type="text" name="destination_airport_code" class="form-control" value="{{ old('destination_airport_code') }}" placeholder="e.g. KHI">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Departure Time *</label>
                        <input type="datetime-local" name="departure_time" class="form-control" value="{{ old('departure_time') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Arrival Time *</label>
                        <input type="datetime-local" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Departure Date *</label>
                        <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Return Date</label>
                        <input type="date" name="return_date" class="form-control" value="{{ old('return_date') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Baggage (KG) *</label>
                        <input type="number" name="baggage_kg" class="form-control" value="{{ old('baggage_kg', 23) }}" min="0" max="100" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Duration (minutes)</label>
                        <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes') }}" min="1">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="delayed" {{ old('status') === 'delayed' ? 'selected' : '' }}>Delayed</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="card-footer text-right">
                <button class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>Save Flight
                </button>
            </div>
        </form>
    </div>
@stop
