@extends('adminlte::page')

@section('title', 'Agent Dashboard')

@section('content_header')
    <h1>Agent Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner"><h3>{{ $stats['bookings'] }}</h3><p>Total Bookings</p></div>
                <div class="icon"><i class="fas fa-suitcase-rolling"></i></div>
                <a href="javascript:void(0)" class="small-box-footer">Overview <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-teal">
                <div class="inner"><h3>{{ number_format($stats['total_revenue'], 2) }}</h3><p>Total Earnings</p></div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <a href="javascript:void(0)" class="small-box-footer">Overview <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner"><h3>{{ number_format($stats['wallet_balance'], 2) }}</h3><p>Wallet Balance</p></div>
                <div class="icon"><i class="fas fa-wallet"></i></div>
                <a href="javascript:void(0)" class="small-box-footer">Overview <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner"><h3>{{ $recentTransactions->count() }}</h3><p>Recent Transactions</p></div>
                <div class="icon"><i class="fas fa-exchange-alt"></i></div>
                <a href="javascript:void(0)" class="small-box-footer">Overview <i class="fas fa-arrow-circle-right"></i></a>
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

    <div class="card">
        <div class="card-header"><b>Recent Transactions</b></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>ID</th><th>Method</th><th>Type</th><th>Amount</th><th>Balance After</th><th>Created</th></tr></thead>
                <tbody>
                @forelse($recentTransactions as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->method }}</td>
                        <td>
                            <span class="badge {{ strtolower($t->type) === 'credit' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($t->type) }}
                            </span>
                        </td>
                        <td>{{ number_format($t->amount, 2) }}</td>
                        <td>{{ number_format($t->balance_after, 2) }}</td>
                        <td>{{ (string) $t->created_at }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted p-3">No transactions found.</td></tr>
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

