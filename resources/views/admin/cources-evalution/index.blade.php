@extends('adminlte::page')

@section('title', 'Courses Evaluation')

@section('content_header')
    <h1 class="m-0">Course Evaluation</h1>
@stop

@section('content')
    <div class="container-fluid">
        {{-- @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        {{-- @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        @if(($questions ?? collect())->isEmpty())
            <div class="alert alert-warning">No evaluation questions found.</div>
        @elseif(($evaluationTargets ?? collect())->isEmpty())
            <div class="alert alert-warning">
                No assigned course/teacher mapping found for your account.
            </div>
        @else
            <form id="courseEvaluationWizard" method="POST" action="{{ route('student.course-evaluation.submit') }}">
                @csrf
                <input type="hidden" name="submissions_payload" id="submissions_payload">

                <div class="card mb-3">
                    <div class="card-header wizard-top d-flex align-items-center">
                        <strong class="mb-0">Step by Step Evaluation</strong>
                        <span id="stepCounter" class="badge badge-info wizard-badge ml-auto"></span>
                    </div>
                    <div class="card-body">
                        <label class="font-weight-bold mb-2">Current Assignment</label>
                        <div class="wizard-target mb-2">
                            <div class="row">
                                <div class="col-md-3"><strong>Teacher Name:</strong> <span id="metaTeacher">-</span></div>
                                <div class="col-md-3"><strong>Class:</strong> <span id="metaClass">-</span></div>
                                <div class="col-md-3"><strong>Session:</strong> <span id="metaSession">-</span></div>
                                <div class="col-md-3"><strong>Course:</strong> <span id="metaCourse">-</span></div>
                            </div>
                        </div>
                        <small class="text-muted">Complete this page and click Next.</small>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        @foreach($questions as $index => $question)
                            <div class="border rounded p-3 mb-3 question-item" data-question-id="{{ $question->evaluation_question_id }}">
                                <div class="font-weight-bold mb-2">
                                    {{ $index + 1 }}. {{ rtrim($question->evaluation_question_title, '?') }}?
                                </div>
                                <div class="d-grid gap-2 option-grid">
                                    @foreach([1 => 'Strongly Disagree', 2 => 'Disagree', 3 => 'Neutral', 4 => 'Agree', 5 => 'Strongly Agree'] as $val => $label)
                                        <label class="btn btn-outline-secondary text-left mb-0">
                                            <input type="radio" class="mr-2" name="q_{{ $question->evaluation_question_id }}" value="{{ $val }}">
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header"><strong>Comments (Optional)</strong></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Course Best Features</label><textarea id="c_course_best_features" class="form-control" rows="2"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Course Improvement Suggestions</label><textarea id="c_course_improvement_sgtions" class="form-control" rows="2"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Course Content Organization</label><textarea id="c_course_content_organization" class="form-control" rows="2"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Student Contribution</label><textarea id="c_student_contribution" class="form-control" rows="2"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Learning Environment</label><textarea id="c_learning_environment" class="form-control" rows="2"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Learning Resources</label><textarea id="c_learning_resources" class="form-control" rows="2"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Delivery Quality</label><textarea id="c_delivery_quality" class="form-control" rows="2"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Assessment Methodology</label><textarea id="c_assessment_ethodology" class="form-control" rows="2"></textarea></div></div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <button type="button" id="prevBtn" class="btn btn-secondary">Previous</button>
                        <div class="ml-auto d-flex align-items-center">
                            <button type="button" id="nextBtn" class="btn btn-info ml-2">Next</button>
                            <button type="button" id="finalSubmitBtn" class="btn btn-primary d-none ml-2">Submit All Evaluations</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
@stop

@section('css')
    <style>
        .wizard-top {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .wizard-badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 6px 10px;
        }

        .wizard-target {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 10px 12px;
            background: #fff;
            min-height: 42px;
            font-weight: 500;
        }

        .option-grid { display: grid; grid-template-columns: repeat(5, minmax(120px, 1fr)); gap: 8px; }
        @media (max-width: 768px) { .option-grid { grid-template-columns: 1fr; } }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (() => {
            const targets = @json(($evaluationTargets ?? collect())->values());
            if (!targets.length) return;

            const questionIds = Array.from(document.querySelectorAll('.question-item')).map(el => Number(el.dataset.questionId));
            const state = { current: 0, byStep: {} };

            const stepCounter = document.getElementById('stepCounter');
            const metaTeacher = document.getElementById('metaTeacher');
            const metaClass = document.getElementById('metaClass');
            const metaSession = document.getElementById('metaSession');
            const metaCourse = document.getElementById('metaCourse');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const finalSubmitBtn = document.getElementById('finalSubmitBtn');

            const commentIds = [
                'c_course_best_features',
                'c_course_improvement_sgtions',
                'c_course_content_organization',
                'c_student_contribution',
                'c_learning_environment',
                'c_learning_resources',
                'c_delivery_quality',
                'c_assessment_ethodology',
            ];

            function readStepData() {
                const answers = {};
                for (const qid of questionIds) {
                    const checked = document.querySelector(`input[name="q_${qid}"]:checked`);
                    answers[qid] = checked ? Number(checked.value) : null;
                }

                const comment = {
                    course_best_features: document.getElementById('c_course_best_features').value,
                    course_improvement_sgtions: document.getElementById('c_course_improvement_sgtions').value,
                    course_content_organization: document.getElementById('c_course_content_organization').value,
                    student_contribution: document.getElementById('c_student_contribution').value,
                    learning_environment: document.getElementById('c_learning_environment').value,
                    learning_resources: document.getElementById('c_learning_resources').value,
                    delivery_quality: document.getElementById('c_delivery_quality').value,
                    assessment_ethodology: document.getElementById('c_assessment_ethodology').value,
                };

                return { answers, comment };
            }

            function validateCurrentStep() {
                const data = readStepData();
                const missing = questionIds.some(qid => !data.answers[qid]);
                if (missing) {
                    Swal.fire({ icon: 'warning', title: 'Incomplete', text: 'Please answer all questions before continuing.' });
                    return false;
                }
                return true;
            }

            function saveCurrentStep() {
                state.byStep[state.current] = readStepData();
            }

            function loadCurrentStep() {
                const target = targets[state.current];
                const saved = state.byStep[state.current] || { answers: {}, comment: {} };

                stepCounter.textContent = `Step ${state.current + 1} of ${targets.length}`;

                const parts = (target.label || '').split('|').map(p => p.trim());
                metaCourse.textContent = target.course_name || parts[0] || '-';
                metaClass.textContent = target.class_name || parts[1] || '-';
                metaSession.textContent = target.session_name || parts[2] || '-';
                metaTeacher.textContent = target.teacher_name || parts[3] || '-';

                for (const qid of questionIds) {
                    const val = saved.answers[qid];
                    const radios = document.querySelectorAll(`input[name="q_${qid}"]`);
                    radios.forEach(r => { r.checked = Number(r.value) === Number(val); });
                }

                commentIds.forEach(id => {
                    const key = id.replace('c_', '');
                    document.getElementById(id).value = saved.comment[key] || '';
                });

                prevBtn.disabled = state.current === 0;
                const isLast = state.current === targets.length - 1;
                nextBtn.classList.toggle('d-none', isLast);
                finalSubmitBtn.classList.toggle('d-none', !isLast);
            }

            prevBtn.addEventListener('click', () => {
                saveCurrentStep();
                if (state.current > 0) {
                    state.current--;
                    loadCurrentStep();
                }
            });

            nextBtn.addEventListener('click', () => {
                if (!validateCurrentStep()) return;
                saveCurrentStep();
                if (state.current < targets.length - 1) {
                    state.current++;
                    loadCurrentStep();
                }
            });

            finalSubmitBtn.addEventListener('click', () => {
                if (!validateCurrentStep()) return;
                saveCurrentStep();

                let firstMissingStep = -1;
                for (let idx = 0; idx < targets.length; idx++) {
                    const step = state.byStep[idx];
                    if (!step) {
                        firstMissingStep = idx;
                        break;
                    }
                    const hasMissingAnswer = questionIds.some(qid => !step.answers || !step.answers[qid]);
                    if (hasMissingAnswer) {
                        firstMissingStep = idx;
                        break;
                    }
                }

                if (firstMissingStep >= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete',
                        text: 'Please fill all courses evaluation before submit.'
                    });
                    state.current = firstMissingStep;
                    loadCurrentStep();
                    return;
                }

                const submissions = targets.map((target, idx) => {
                    const step = state.byStep[idx];
                    return {
                        teacher_id: target.teacher_id,
                        session_id: target.session_id,
                        class_id: target.class_id,
                        course_id: target.course_id,
                        answers: questionIds.map(qid => ({
                            question_id: qid,
                            option_id: step.answers[qid],
                        })),
                        comment: step.comment,
                    };
                });

                document.getElementById('submissions_payload').value = JSON.stringify(submissions);
                document.getElementById('courseEvaluationWizard').submit();
            });

            loadCurrentStep();
        })();

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Submitted', text: @json(session('success')), confirmButtonText: 'OK' });
        @endif
    </script>
@stop
