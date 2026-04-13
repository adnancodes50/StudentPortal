@extends('layouts.app')

@section('title', $category->name . ' - Airline Ticket System')

@section('content')

<style>
/* ===== Flight Table Style ===== */
.flight-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.flight-table thead {
    background: #000;
    color: #fff;
}

.flight-table th, .flight-table td {
    padding: 15px;
    text-align: center;
}

.flight-row {
    background: #eaf3f7;
    border-bottom: 10px solid #fff;
    transition: 0.3s;
}

.flight-row:hover {
    background: #d9ecf5;
}

.login-btn {
    background: #e21b1b;
    color: #fff;
    border: none;
    padding: 10px 25px;
    border-radius: 6px;
    font-weight: bold;
}

.login-btn:hover {
    background: #c91515;
}

.copy-btn {
    background: #28a745;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
}
</style>

<div class="container mt-4">

<!-- Breadcrumb -->
<nav>
    <a href="{{ url('/') }}">Home</a> / {{ $category->name }}
</nav>

<!-- Title -->
<h2 class="mt-3">{{ $category->name }} Flights ✈️</h2>

<!-- Flights Table -->
@if(isset($category->flights) && $category->flights->count())

    <table class="flight-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Flight</th>
                <th>Time</th>
                <th>Bag</th>
                <th>Meal</th>
                <th>Fare</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($category->flights as $flight)
                <tr class="flight-row">

                    <td>
                        {{ $flight->departure_date ?? 'N/A' }}<br>
                        {{ $flight->return_date ?? '' }}
                    </td>

                    <td>
                        {{ $flight->flight_number ?? 'SV000' }}
                    </td>

                    <td>
                        {{ $flight->departure_time ?? '00:00' }} -
                        {{ $flight->arrival_time ?? '00:00' }}
                    </td>

                    <td>
                        {{ $flight->baggage ?? '23KG' }}
                    </td>

                    <td style="color:green;font-weight:bold;">
                        {{ $flight->meal ?? 'YES' }}
                    </td>

                    <td style="font-weight:bold;">
                        PKR {{ number_format($flight->price ?? 0) }}
                    </td>

                    <td>
    {{-- <button class="copy-btn">Copy</button> --}}
    <br><br>

    @auth
        <a href="#" class="btn btn-success">Book Flight</a>
    @else
        <a href="{{ route('login') }}" class="login-btn">Login</a>
    @endauth
</td>

                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <div class="alert alert-info mt-3">
        No flights available in this category.
    </div>
@endif

<div class="mt-4">
    <a href="{{ url('/') }}" class="btn btn-secondary">← Back</a>
</div>

</div>

@endsection
