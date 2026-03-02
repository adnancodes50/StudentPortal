@extends('adminlte::page')

@section('title', 'Courses Evaluation')

@section('content_header')
    <div class="eval-header">
        <h1 class="m-0">Course Evaluation</h1>
        <p class="mb-0">Please select one option for each question.</p>
    </div>
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
            <form method="POST" action="{{ route('student.course-evaluation.submit') }}">
                @csrf

                <div class="card eval-card mb-4">
                    <div class="card-body">
                        @if(($evaluationTargets ?? collect())->isNotEmpty())
                            <div class="form-group mb-0">
                                <label class="eval-label" for="evaluation_target">Course / Class / Session / Teacher</label>
                                <select class="form-control eval-select" id="evaluation_target" required>
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
                            <div class="alert alert-warning mb-0">
                                No assigned course/teacher mapping found for your account.
                                Please contact admin to assign teacher/class/session/course.
                            </div>
                        @endif

                        <input type="hidden" name="teacher_id" id="teacher_id" value="{{ old('teacher_id') }}">
                        <input type="hidden" name="session_id" id="session_id" value="{{ old('session_id') }}">
                        <input type="hidden" name="class_id" id="class_id" value="{{ old('class_id') }}">
                        <input type="hidden" name="course_id" id="course_id" value="{{ old('course_id') }}">
                    </div>
                </div>

                <div class="card eval-card mb-4">
                   
                    <div class="card-body">
                        @foreach($questions as $index => $question)
                            <div class="question-item">
                                <div class="question-title">
                                    <span class="question-no">{{ $index + 1 }}</span>
                                    <span>{{ rtrim($question->evaluation_question_title, '?') }}?</span>
                                </div>

                                <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->evaluation_question_id }}">

                                <div class="option-grid">
                                    @for($option = 1; $option <= 5; $option++)
                                        <label class="option-pill">
                                            <input
                                                type="radio"
                                                name="answers[{{ $index }}][option_id]"
                                                value="{{ $option }}"
                                                required
                                                {{ (string) old("answers.$index.option_id") === (string) $option ? 'checked' : '' }}
                                            >
                                            <span>
                                                @if($option === 1)
                                                    Strongly Disagree
                                                @elseif($option === 2)
                                                    Disagree
                                                @elseif($option === 3)
                                                    Neutral
                                                @elseif($option === 4)
                                                    Agree
                                                @else
                                                    Strongly Agree
                                                @endif
                                            </span>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card eval-card mb-4">
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
                        <button type="submit" class="btn btn-primary px-4" {{ ($evaluationTargets ?? collect())->isEmpty() ? 'disabled' : '' }}>
                            Submit Evaluation
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@stop

@section('css')
    <style>
        .eval-header {
            padding: 8px 0;
            color: #1f2933;
            background: transparent;
            border: 0;
        }

        .eval-header p {
            color: #5f6c7b;
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .eval-card {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .eval-label {
            font-weight: 700;
            color: #212529;
        }

        .eval-select {
            border: 1px solid #ced4da;
            background-color: #fff;
        }

        .eval-legend {
            display: grid;
            grid-template-columns: repeat(5, minmax(120px, 1fr));
            gap: 8px;
            font-size: 0.8rem;
            color: #495057;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .question-item {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 14px;
            margin-bottom: 12px;
            background: #fff;
        }

        .question-item:last-child {
            margin-bottom: 0;
        }

        .question-title {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
            color: #212529;
            font-weight: 600;
        }

        .question-no {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #6c757d;
            color: #fff;
            font-size: 0.75rem;
        }

        .option-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(140px, 1fr));
            gap: 8px;
        }

        .option-pill input {
            display: none;
        }

        .option-pill span {
            display: block;
            text-align: center;
            padding: 8px 6px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background: #fff;
            color: #343a40;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.1s ease-in-out;
        }

        .option-pill input:checked + span {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        @media (max-width: 768px) {
            .eval-legend {
                grid-template-columns: 1fr 1fr;
            }

            .option-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Submitted',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@stop
