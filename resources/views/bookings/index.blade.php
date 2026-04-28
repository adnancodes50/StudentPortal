@extends('adminlte::page')

@section('title', 'Bookings')

@section('content_header')
    <h1>Bookings</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">Booking List ({{ strtoupper($role) }})</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->reference_no }}</td>
                            <td>{{ $booking->booking_type }}</td>
                            <td>{{ $booking->status }}</td>
                            <td>{{ number_format((float) $booking->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $bookings->links() }}</div>
    </div>
@stop
