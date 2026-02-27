@extends('adminlte::page')

@section('title', 'Student Attendence')

@section('content_header')
    <h1 class="m-0">Student Attendence</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if(($assignedCourses ?? collect())->isEmpty())
            <div class="alert alert-warning mb-3">
                No courses are assigned yet, so attendance cannot be shown.
            </div>
        @elseif(($attendanceSummary ?? collect())->isEmpty())
            <div class="alert alert-info mb-3">
                No attendance records found for your registered courses.
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance Portal</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-sm mb-0 attendance-grid">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">SR#</th>
                                    <th>LEC</th>
                                    @foreach(($lectureNumbers ?? []) as $lectureNo)
                                        <th class="text-center">{{ $lectureNo }}</th>
                                    @endforeach
                                    <th class="text-center">T</th>
                                    <th class="text-center">P</th>
                                    <th class="text-center">A</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceSummary as $index => $row)
                                    <tr>
                                        <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                        <td class="font-weight-bold">
                                            {{ $row['course']?->course_title ?? 'N/A' }}
                                        </td>
                                        @foreach(($lectureNumbers ?? []) as $lectureNo)
                                            @php($mark = $row['lecture_status'][$lectureNo] ?? null)
                                            <td class="text-center {{ $mark === 'P' ? 'att-p' : ($mark === 'A' ? 'att-a' : '') }}">
                                                {{ $mark ?? '' }}
                                            </td>
                                        @endforeach
                                        <td class="text-center font-weight-bold">{{ $row['taken_count'] }}</td>
                                        <td class="text-center font-weight-bold att-p">{{ $row['present_count'] }}</td>
                                        <td class="text-center font-weight-bold att-a">{{ $row['absent_count'] }}</td>
                                        <td class="text-center font-weight-bold">{{ $row['percentage'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .attendance-grid th,
        .attendance-grid td {
            min-width: 36px;
            vertical-align: middle;
            white-space: nowrap;
        }

        .attendance-grid th:nth-child(2),
        .attendance-grid td:nth-child(2) {
            min-width: 260px;
        }

        .attendance-grid .att-p {
            background-color: #d8f0d2;
            color: #175c1a;
        }

        .attendance-grid .att-a {
            background-color: #f6dddd;
            color: #7a1c1c;
        }
    </style>
@stop
