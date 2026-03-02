@extends('adminlte::page')

@section('title', 'Teacher Evaluation')

@section('content_header')
    <h1 class="m-0">Teacher Evaluation</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(($questions ?? collect())->isEmpty())
            <div class="alert alert-warning">
                No teacher evaluation questions found.
            </div>
        @else
            <form method="POST" action="{{ route('student.teacher-evaluation.submit') }}">
                @csrf

                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Select Course and Teacher</h3>
                    </div>
                    <div class="card-body">
                        @if(($evaluationTargets ?? collect())->isNotEmpty())
                            <div class="form-group mb-0">
                                <label for="evaluation_target">Course / Class / Session / Teacher</label>
                                <select class="form-control" id="evaluation_target" required>
                                    <option value="">Select an assigned course</option>
                                    @foreach($evaluationTargets as $target)
                                        <option
                                            value="{{ $target['teacher_id'] }}|{{ $target['session_id'] }}|{{ $target['class_id'] }}|{{ $target['course_id'] }}"
                                            data-teacher="{{ $target['teacher_id'] }}"
                                            data-session="{{ $target['session_id'] }}"
                                            data-class="{{ $target['class_id'] }}"
                                            data-course="{{ $target['course_id'] }}"
                                            {{ old('teacher_id') == $target['teacher_id'] && old('session_id') == $target['session_id'] && old('class_id') == $target['class_id'] && old('course_id') == $target['course_id'] ? 'selected' : '' }}
                                        >
                                            {{ $target['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="alert alert-warning mb-3">
                                No assigned course/teacher mapping found for your account.
                                Please contact admin to assign teacher/class/session/course.
                            </div>
                            <div class="form-group mb-0">
                                <label for="evaluation_target_empty">Course / Class / Session / Teacher</label>
                                <select class="form-control" id="evaluation_target_empty" disabled>
                                    <option>No assigned mapping available</option>
                                </select>
                            </div>
                        @endif

                        <input type="hidden" name="teacher_id" id="teacher_id" value="{{ old('teacher_id') }}">
                        <input type="hidden" name="session_id" id="session_id" value="{{ old('session_id') }}">
                        <input type="hidden" name="class_id" id="class_id" value="{{ old('class_id') }}">
                        <input type="hidden" name="course_id" id="course_id" value="{{ old('course_id') }}">
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Questions</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="min-width: 320px;">Question</th>
                                        <th class="text-center">Strongly Disagree</th>
                                        <th class="text-center">Disagree</th>
                                        <th class="text-center">Neutral</th>
                                        <th class="text-center">Agree</th>
                                        <th class="text-center">Strongly Agree</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questions as $index => $question)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->evaluation_question_id }}">
                                                {{ $question->evaluation_question_title }}
                                            </td>
                                            @for($option = 1; $option <= 5; $option++)
                                                <td class="text-center">
                                                    <input
                                                        type="radio"
                                                        name="answers[{{ $index }}][option_id]"
                                                        value="{{ $option }}"
                                                        required
                                                        {{ (string) old("answers.$index.option_id") === (string) $option ? 'checked' : '' }}
                                                    >
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Additional Comments (Optional)</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Course Comment</label>
                                    <textarea class="form-control" name="comment[evaluation_comment_course]" rows="3">{{ old('comment.evaluation_comment_course') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Instructor Comment</label>
                                    <textarea class="form-control" name="comment[evaluation_comment_instructer]" rows="3">{{ old('comment.evaluation_comment_instructer') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" {{ ($evaluationTargets ?? collect())->isEmpty() ? 'disabled' : '' }}>
                            Submit Evaluation
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@stop

@section('js')
    <script>
        (function () {
            const targetSelect = document.getElementById('evaluation_target');
            const teacherIdField = document.getElementById('teacher_id');
            const sessionIdField = document.getElementById('session_id');
            const classIdField = document.getElementById('class_id');
            const courseIdField = document.getElementById('course_id');

            function syncFromSelect() {
                if (!targetSelect) {
                    return;
                }

                const selected = targetSelect.options[targetSelect.selectedIndex];

                teacherIdField.value = selected ? (selected.dataset.teacher || '') : '';
                sessionIdField.value = selected ? (selected.dataset.session || '') : '';
                classIdField.value = selected ? (selected.dataset.class || '') : '';
                courseIdField.value = selected ? (selected.dataset.course || '') : '';
            }

            if (targetSelect) {
                targetSelect.addEventListener('change', syncFromSelect);
                syncFromSelect();
            }
        })();
    </script>
@stop
