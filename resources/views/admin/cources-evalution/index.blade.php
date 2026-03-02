@extends('adminlte::page')

@section('title', 'Courses Evaluation')

@section('content_header')
    <h1 class="m-0">Course Evaluation</h1>
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
                No evaluation questions found.
            </div>
        @else
            {{-- @if(($evaluationTargets ?? collect())->isNotEmpty())
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Assigned Teachers and Classes</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Teacher</th>
                                        <th>Class</th>
                                        <th>Course</th>
                                        <th>Session</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evaluationTargets as $idx => $target)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td>{{ $target['teacher_name'] ?? 'Teacher Not Found' }}</td>
                                            <td>{{ $target['class_name'] ?? ('Class #' . ($target['class_id'] ?? '')) }}</td>
                                            <td>{{ $target['course_name'] ?? ('Course #' . ($target['course_id'] ?? '')) }}</td>
                                            <td>{{ $target['session_name'] ?? ('Session #' . ($target['session_id'] ?? '')) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif --}}

            <form method="POST" action="{{ route('student.course-evaluation.submit') }}">
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
                                                {{ $question->evaluation_question_title }}?
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
                                    <label>Course Best Features</label>
                                    <textarea class="form-control" name="comment[course_best_features]" rows="2">{{ old('comment.course_best_features') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Course Improvement Suggestions</label>
                                    <textarea class="form-control" name="comment[course_improvement_sgtions]" rows="2">{{ old('comment.course_improvement_sgtions') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Course Content Organization</label>
                                    <textarea class="form-control" name="comment[course_content_organization]" rows="2">{{ old('comment.course_content_organization') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Student Contribution</label>
                                    <textarea class="form-control" name="comment[student_contribution]" rows="2">{{ old('comment.student_contribution') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Learning Environment</label>
                                    <textarea class="form-control" name="comment[learning_environment]" rows="2">{{ old('comment.learning_environment') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Learning Resources</label>
                                    <textarea class="form-control" name="comment[learning_resources]" rows="2">{{ old('comment.learning_resources') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Quality</label>
                                    <textarea class="form-control" name="comment[delivery_quality]" rows="2">{{ old('comment.delivery_quality') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Assessment Methodology</label>
                                    <textarea class="form-control" name="comment[assessment_ethodology]" rows="2">{{ old('comment.assessment_ethodology') }}</textarea>
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
