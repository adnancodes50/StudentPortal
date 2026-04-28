@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Admin Dashboard</h1>
        @if(session()->has('impersonator_id'))
            <form method="POST" action="{{ route('admin.impersonate.stop') }}">
                @csrf
                <button class="btn btn-warning btn-sm">Exit Impersonation</button>
            </form>
        @endif
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-teal">
                <div class="inner"><h3>{{ number_format($stats['total_revenue'], 2) }}</h3><p>Total Revenue</p></div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <a href="{{ route('admin.payments.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner"><h3>{{ $stats['agents'] }}</h3><p>Agents</p></div>
                <div class="icon"><i class="fas fa-user-tie"></i></div>
                <a href="{{ route('admin.agents.index') }}" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner"><h3>{{ $stats['users'] }}</h3><p>Users</p></div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">Manage <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner"><h3>{{ $stats['bookings'] }}</h3><p>Bookings</p></div>
                <div class="icon"><i class="fas fa-suitcase-rolling"></i></div>
                <a href="{{ route('admin.bookings.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-secondary">
                <div class="inner"><h3>{{ $stats['pending_bookings'] }}</h3><p>Pending</p></div>
                <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                <a href="{{ route('admin.bookings.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-indigo">
                <div class="inner"><h3>{{ $stats['draft_bookings'] }}</h3><p>Draft</p></div>
                <div class="icon"><i class="fas fa-file-alt"></i></div>
                <a href="{{ route('admin.bookings.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner"><h3>{{ $stats['confirmed_bookings'] }}</h3><p>Confirmed</p></div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ route('admin.bookings.index') }}" class="small-box-footer">Filter <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner"><h3>{{ $stats['cancelled_bookings'] }}</h3><p>Cancelled</p></div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
                <a href="{{ route('admin.bookings.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><b>Booking Status Chart</b></div>
                <div class="card-body">
                    <canvas id="statusChart" height="180"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><b>Booking Type Chart</b></div>
                <div class="card-body">
                    <canvas id="typeChart" height="180"></canvas>
                </div>
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
                    <tr><td colspan="6" class="text-center text-muted p-3">No data</td></tr>
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
                    datasets: [{
                        data: @json($typeChart['values']),
                        backgroundColor: ['#17a2b8', '#28a745', '#007bff', '#ffc107', '#dc3545', '#6610f2', '#20c997']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    </script>
@stop

