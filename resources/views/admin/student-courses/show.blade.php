@extends('adminlte::page')

@section('title', 'Course Details')

@section('content_header')
    <h1>Course Details</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $course->course->course_title ?? 'N/A' }}</h3>
    </div>

    <div class="card-body">
        <p><strong>Course Code:</strong> {{ $course->course->course_code ?? 'N/A' }}</p>
        <p><strong>Credit Hours:</strong> {{ $course->course->course_credit_hours ?? 'N/A' }}</p>
        <p><strong>Class:</strong> {{ $course->class->class_name ?? 'N/A' }}</p>
        <p><strong>Session:</strong>
            {{ $course->session->session_type ?? '' }}
            {{ $course->session->session_year ?? '' }}
            {{ $course->session->session_timing ?? '' }}
        </p>
        <p><strong>Section:</strong> {{ $course->student_section ?? 'N/A' }}</p>
        <p><strong>Teacher Name:</strong> {{ $course->resolved_teacher_name ?? 'Not Found' }}</p>
        <p><strong>Teacher Email:</strong> {{ $course->resolved_teacher_email ?? 'Not Found' }}</p>
    </div>

    <div class="card-footer">
        <a href="{{ route('student.courses') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

@stop
