@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0">Student Dashboard</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <h4 class="mb-1 font-weight-bold text-truncate">{{ $registeredCoursesCount }}</h4>
                        <p class="mb-0">Registered Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <h4 class="mb-1 font-weight-bold text-truncate">{{ $currentClass }}</h4>
                        <p class="mb-0">Current Class</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card bg-warning h-100">
                    <div class="card-body">
                        <h4 class="mb-1 font-weight-bold text-truncate">{{ $currentSemester }}</h4>
                        <p class="mb-0">Current Semester</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <h4 class="mb-1 font-weight-bold text-truncate">{{ $currentSession }}</h4>
                        <p class="mb-0">Current Session</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
