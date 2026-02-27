@extends('adminlte::page')

@section('title', 'Student Courses')

@section('content_header')
    {{-- <h1 class="m-0">Registered Courses</h1> --}}
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Registered Courses</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Credit Hours</th>
                                        <th>Teacher</th>
                                        <th>Email</th>
                                        <th>Class</th>
                                        <th>Session</th>
                                        <th>Section</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignedCourses as $index => $course)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $course->course->course_code ?? 'Course #' . $course->course_id }}</td>
                                            <td>{{ $course->course->course_title ?? '-' }}</td>
                                            <td>[ {{ $course->course->course_credit_hours ?? '-' }} ]</td>
                                            <td>{{ $course->resolved_teacher_name ?? 'Not Found' }}</td>
                                            <td>{{ $course->resolved_teacher_email ?? 'Not Found' }}</td>
                                            <td>{{ $course->class->class_name ?? ('Class #' . $course->class_id) }}</td>
                                            <td>
                                                @if($course->session)
                                                    {{ trim(($course->session->session_type ?? '') . ' ' . ($course->session->session_year ?? '') . ' ' . ($course->session->session_timing ?? '')) }}
                                                @else
                                                    {{ 'Session #' . $course->session_id }}
                                                @endif
                                            </td>
                                            <td>{{ $course->student_section ?? '-' }}</td>
                                            <td>
                                               <a href="#" class="btn btn-info btn-sm">Details</a> {{-- <a href="{{ route('admin.student-courses.show', $course->id) }}" class="btn btn-sm btn-info">View</a> --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                No courses assigned to this student.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
