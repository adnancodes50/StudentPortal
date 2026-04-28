@extends('adminlte::page')

@section('title', 'Agent Report')

@section('content_header')
    <h1>Agent Report: {{ $agent->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3"><div class="small-box bg-info"><div class="inner"><h3>{{ $stats['total_bookings'] }}</h3><p>Total Bookings</p></div><div class="icon"><i class="fas fa-suitcase-rolling"></i></div></div></div>
        <div class="col-md-3"><div class="small-box bg-success"><div class="inner"><h3>{{ number_format($stats['total_amount'],2) }}</h3><p>Total Booking Amount</p></div><div class="icon"><i class="fas fa-coins"></i></div></div></div>
        <div class="col-md-3"><div class="small-box bg-primary"><div class="inner"><h3>{{ number_format($stats['received_amount'],2) }}</h3><p>Total Received</p></div><div class="icon"><i class="fas fa-money-bill-wave"></i></div></div></div>
        <div class="col-md-3"><div class="small-box bg-warning"><div class="inner"><h3>{{ $stats['confirmed'] }}</h3><p>Confirmed</p></div><div class="icon"><i class="fas fa-check-circle"></i></div></div></div>
    </div>

    <div class="row">
        <div class="col-md-3"><div class="small-box bg-secondary"><div class="inner"><h3>{{ $stats['pending'] }}</h3><p>Pending</p></div></div></div>
        <div class="col-md-3"><div class="small-box bg-indigo"><div class="inner"><h3>{{ $stats['draft'] }}</h3><p>Draft</p></div></div></div>
        <div class="col-md-3"><div class="small-box bg-danger"><div class="inner"><h3>{{ $stats['cancelled'] }}</h3><p>Cancelled</p></div></div></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><b>Status Chart</b></div>
                <div class="card-body"><canvas id="statusChart" height="180"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><b>Booking Type Chart</b></div>
                <div class="card-body"><canvas id="typeChart" height="180"></canvas></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><b>Recent Bookings</b></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>ID</th><th>Ref</th><th>Type</th><th>Status</th><th>Total</th><th>Created</th></tr></thead>
                <tbody>
                @forelse($recentBookings as $b)
                    <tr>
                        <td>{{ $b->id }}</td>
                        <td>{{ $b->reference_no ?? $b->booking_no }}</td>
                        <td>{{ $b->booking_type }}</td>
                        <td>{{ $b->status }}</td>
                        <td>{{ $b->total_amount }}</td>
                        <td>{{ optional($b->created_at)->toDateTimeString() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted p-3">No bookings</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: @json($statusChart['labels']),
                datasets: [{
                    label: 'Bookings',
                    data: @json($statusChart['values']),
                    backgroundColor: ['#6c757d', '#6610f2', '#ffc107', '#dc3545']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    const typeCtx = document.getElementById('typeChart');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: @json($typeChart['labels']),
                datasets: [{ data: @json($typeChart['values']) }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
</script>
@stop

