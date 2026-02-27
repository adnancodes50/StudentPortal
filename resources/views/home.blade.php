@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        <h1 class="m-0 font-weight-bold">Student Dashboard</h1>
        <span class="dashboard-subtitle">Academic Overview</span>
    </div>
@stop

@section('content')
    <div class="container-fluid dashboard-wrap">
        <div class="row">
            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stat-card stat-card-courses h-100">
                    <div class="card-body">
                        <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                        <div>
                            <h4 class="mb-1 font-weight-bold text-truncate">{{ $registeredCoursesCount }}</h4>
                            <p class="mb-0">Registered Courses</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stat-card stat-card-class h-100">
                    <div class="card-body">
                        <div class="stat-icon"><i class="fas fa-university"></i></div>
                        <div>
                            <h4 class="mb-1 font-weight-bold text-truncate">{{ $currentClass }}</h4>
                            <p class="mb-0">Current Class</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stat-card stat-card-semester h-100">
                    <div class="card-body">
                        <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <h4 class="mb-1 font-weight-bold text-truncate">{{ $currentSemester }}</h4>
                            <p class="mb-0">Current Semester</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stat-card stat-card-session h-100">
                    <div class="card-body">
                        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <h4 class="mb-1 font-weight-bold stat-session-value">{{ $currentSession }}</h4>
                            <p class="mb-0">Joining Session</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .dashboard-wrap {
            background: linear-gradient(135deg, #f5f7fa 0%, #eef2f7 100%);
            border-radius: 12px;
            padding: 12px;
        }

        .dashboard-subtitle {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2d4c6b;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .stat-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(26, 39, 62, 0.12);
            overflow: hidden;
        }

        .stat-card .card-body {
            min-height: 120px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.18);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .stat-card h4 {
            font-size: 1.9rem;
            line-height: 1.1;
            margin-bottom: 0.35rem;
        }

        .stat-card p {
            font-size: 1rem;
            opacity: 0.95;
        }

        .stat-card-courses {
            color: #fff;
            background: linear-gradient(135deg, #0e7490 0%, #0891b2 100%);
        }

        .stat-card-class {
            color: #fff;
            background: linear-gradient(135deg, #17653a 0%, #1f8a4b 100%);
        }

        .stat-card-semester {
            color: #1f2d3d;
            background: linear-gradient(135deg, #f6c343 0%, #ffd166 100%);
        }

        .stat-card-session {
            color: #fff;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }

        .stat-session-value {
            font-size: 2rem;
            line-height: 1.08;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        @media (max-width: 1399.98px) {
            .stat-session-value {
                font-size: 1.7rem;
            }
        }
    </style>
@stop
