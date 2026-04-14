@extends('adminlte::page')

@section('title', 'Flights')

@section('content')

<div class="card">


{{-- HEADER --}}
<div class="card-header bg-primary d-flex align-items-center">
    <h5 class="mb-0 text-white">
        <i class="fas fa-plane"></i> Flights Management
    </h5>

    <a href="{{ route('admin.flights.create') }}"
       class="btn btn-info btn-sm ml-auto">
        <i class="fas fa-plus"></i> Add Flight
    </a>
</div>

{{-- BODY --}}
<div class="card-body">

    {{-- TOP BAR --}}
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
    <div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead style="background-color:#f1f1f1;">
            <tr>
                <th>#</th>
                <th>Airline</th>
                <th>Flight No</th>
                <th>Route</th>
                <th>Dates</th>
                <th>Baggage</th>
                <th>Price</th>
                <th width="140">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($flights as $index => $flight)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td>{{ $flight->airline_name }}</td>

                <td>
                    <strong>{{ $flight->flight_number }}</strong>
                </td>

                <td>
                    {{ $flight->departure_city }} → {{ $flight->arrival_city }}
                </td>

                {{-- NEW: DATES --}}
                <td>
                    <small>
                        <strong>Dep:</strong> {{ $flight->departure_date ?? 'N/A' }} <br>
                        <strong>Ret:</strong> {{ $flight->return_date ?? '-' }}
                    </small>
                </td>

                {{-- NEW: BAGGAGE --}}
                <td>
                    {{ $flight->baggage_kg ?? 0 }} KG
                </td>

                <td>
                    <span class="badge badge-success">
                        PKR {{ number_format(optional($flight->flightPrices->first())->price ?? 0) }}
                    </span>
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
                        <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Delete this flight?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">
                    No flights found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

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
