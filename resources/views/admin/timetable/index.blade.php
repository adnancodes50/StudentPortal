@extends('adminlte::page')

@section('title', 'Time Table')

@section('content_header')
    <h1 class="m-0">Student Time Table</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if(($assignedCourses ?? collect())->isEmpty())
            <div class="alert alert-warning mb-3">
                No courses are assigned yet, so timetable cannot be shown.
            </div>
        @elseif(($timeTable ?? collect())->isEmpty())
            <div class="alert alert-info mb-3">
                No timetable found for your assigned courses.
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Weekly Schedule</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <td>IDs</td>
                                <th>Day</th>
                                {{-- <th>Lecture #</th> --}}
                                <th>Course</th>
                                <th class="d-none d-md-table-cell">Class</th>
                                <th>Section</th>
                                <th class="d-none d-md-table-cell">Room</th>
                                <th>Time</th>
                                <th class="d-none d-lg-table-cell">Session</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timeTable as $index => $slot)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $slot->day ?? 'N/A' }}</td>
                                    {{-- <td>{{ $slot->lec_no ?? 'N/A' }}</td> --}}
                                    <td>
                                        {{ $slot->course->course_code ?? 'N/A' }}
                                        @if(!empty($slot->course->course_title))
                                            - {{ $slot->course->course_title }}
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $slot->class->class_name ?? 'N/A' }}</td>
                                    <td>{{ $slot->section ?: 'N/A' }}</td>
                                    <td class="d-none d-md-table-cell">{{ $slot->room->room_no ?? 'N/A' }}</td>
                                    <td>{{ $slot->time_from ?? 'N/A' }} - {{ $slot->time_to ?? 'N/A' }}</td>
                                    <td class="d-none d-lg-table-cell">
                                        @if($slot->session)
                                            {{ trim(($slot->session->session_type ?? '') . ' ' . ($slot->session->session_year ?? '') . ' ' . ($slot->session->session_timing ?? '')) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card-footer text-muted py-2">
                    On small screens, swipe horizontally to view full table.
                </div> --}}
            </div>
        @endif
    </div>
@stop
