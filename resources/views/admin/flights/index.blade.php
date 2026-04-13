@extends('adminlte::page')

@section('title', 'Flights')

@section('content')

<div class="card">

    {{-- HEADER (LIKE USER LIST) --}}
    <div class="card-header bg-primary d-flex align-items-center">

    {{-- LEFT --}}
    <h5 class="mb-0 text-white">
        <i class="fas fa-plane"></i> Flights Management
    </h5>

    {{-- RIGHT --}}
    <a href="{{ route('admin.flights.create') }}"
       class="btn btn-info btn-sm ml-auto">
        <i class="fas fa-plus"></i> Add Flight
    </a>

</div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- OPTIONAL TOP BAR (LIKE USER LIST) --}}
        <div class="d-flex justify-content-between mb-3">
            <div>
                Show 
                <select class="form-control d-inline-block" style="width:80px;">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                entries
            </div>

            <div>
                Search:
                <input type="text" class="form-control d-inline-block" style="width:200px;">
            </div>
        </div>

        {{-- TABLE --}}
        <table class="table table-bordered table-hover">
            <thead style="background-color:#f1f1f1;">
                <tr>
                    <th>#</th>
                    <th>Airline</th>
                    <th>Flight No</th>
                    <th>Route</th>
                    <th>Price</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($flights as $index => $flight)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $flight->airline_name }}</td>
                    <td>{{ $flight->flight_number }}</td>
                    <td>{{ $flight->departure_city }} → {{ $flight->arrival_city }}</td>
                    <td>
                        {{ optional($flight->flightPrices->first())->price ?? 'N/A' }}
                    </td>
                    <td>

                        {{-- EDIT --}}
                        <a href="{{ route('admin.flights.edit',$flight->id) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('admin.flights.delete',$flight->id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        {{-- FOOTER --}}
        <div class="d-flex justify-content-between">
            <span>Showing {{ count($flights) }} entries</span>

            <div>
                <button class="btn btn-sm btn-light">Previous</button>
                <button class="btn btn-sm btn-success">1</button>
                <button class="btn btn-sm btn-light">Next</button>
            </div>
        </div>

    </div>
</div>

@stop