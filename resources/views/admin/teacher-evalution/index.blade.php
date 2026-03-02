@extends('adminlte::page')

@section('title', 'Teacher Evaluation')

@section('content_header')
    <h1 class="m-0">Teacher Evaluation</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if(($questions ?? collect())->isEmpty())
            <div class="alert alert-danger">No teacher evaluation questions found.</div>
        @elseif(($evaluationTargets ?? collect())->isEmpty())
            <div class="alert alert-danger">You have already submitted all teacher evaluations.</div>
        @else
            <form id="teacherEvaluationWizard" method="POST" action="{{ route('student.teacher-evaluation.submit') }}">
                @csrf
                <input type="hidden" name="submissions_payload" id="submissions_payload">
                <input type="hidden" name="current_step" id="current_step" value="0">

                <div class="card mb-3">
                    <div class="card-header wizard-top d-flex align-items-center">
                        <strong class="mb-0">Step by Step Evaluation</strong>
                        <span id="stepCounter" class="badge badge-info ml-auto"></span>
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
                            <div class="col-md-6"><div class="form-group"><label>Course Comment</label><textarea id="c_evaluation_comment_course" class="form-control" rows="4"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Instructor Comment</label><textarea id="c_evaluation_comment_instructor" class="form-control" rows="4"></textarea></div></div>
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

        .btn-outline-secondary.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (() => {
            const targets = @json(($evaluationTargets ?? collect())->values());
            if (!targets.length) return;

            const questionIds = Array.from(document.querySelectorAll('.question-item')).map(el => Number(el.dataset.questionId));

            // Check which targets are already submitted
            const checkSubmittedStatus = async () => {
                try {
                    const response = await fetch('{{ route("student.teacher-evaluation.check-submitted") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ targets: targets })
                    });
                    const data = await response.json();
                    return data.submitted;
                } catch (error) {
                    console.error('Error checking submitted status:', error);
                    return [];
                }
            };

            const state = {
                current: 0,
                byStep: {},
                submittedTargets: []
            };

            const stepCounter = document.getElementById('stepCounter');
            const metaTeacher = document.getElementById('metaTeacher');
            const metaClass = document.getElementById('metaClass');
            const metaSession = document.getElementById('metaSession');
            const metaCourse = document.getElementById('metaCourse');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const finalSubmitBtn = document.getElementById('finalSubmitBtn');
            const currentStepInput = document.getElementById('current_step');

            const commentIds = ['c_evaluation_comment_course', 'c_evaluation_comment_instructor'];

            // Load saved data from localStorage
            function loadSavedData() {
                const saved = localStorage.getItem('teacherEvaluationState');
                if (saved) {
                    try {
                        const parsed = JSON.parse(saved);
                        if (parsed.byStep) {
                            state.byStep = parsed.byStep;
                        }
                        if (parsed.current !== undefined && parsed.current < targets.length) {
                            state.current = parsed.current;
                        }
                    } catch (e) {
                        console.error('Error loading saved data:', e);
                    }
                }
            }

            // Save data to localStorage
            function saveToLocalStorage() {
                localStorage.setItem('teacherEvaluationState', JSON.stringify({
                    byStep: state.byStep,
                    current: state.current
                }));
            }

            function readStepData() {
                const answers = {};
                for (const qid of questionIds) {
                    const checked = document.querySelector(`input[name="q_${qid}"]:checked`);
                    answers[qid] = checked ? Number(checked.value) : null;
                }

                const comment = {
                    evaluation_comment_course: document.getElementById('c_evaluation_comment_course').value,
                    evaluation_comment_instructor: document.getElementById('c_evaluation_comment_instructor').value,
                };

                return { answers, comment };
            }

            function validateCurrentStep() {
                const data = readStepData();
                const missing = questionIds.some(qid => !data.answers[qid]);
                if (missing) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete',
                        text: 'Please answer all questions before continuing.'
                    });
                    return false;
                }
                return true;
            }

            function saveCurrentStep() {
                state.byStep[state.current] = readStepData();
                saveToLocalStorage();
            }

            function loadCurrentStep() {
                const target = targets[state.current];
                const saved = state.byStep[state.current] || { answers: {}, comment: {} };

                stepCounter.textContent = `Step ${state.current + 1} of ${targets.length}`;

                metaCourse.textContent = target.course_name || '-';
                metaClass.textContent = target.class_name || '-';
                metaSession.textContent = target.session_name || '-';
                metaTeacher.textContent = target.teacher_name || '-';

                // Check if this target is already submitted
                const isSubmitted = state.submittedTargets.some(t =>
                    t.course_id === target.course_id &&
                    t.class_id === target.class_id &&
                    t.session_id === target.session_id &&
                    t.teacher_id === target.teacher_id
                );

                if (isSubmitted) {
                    // Disable all inputs
                    document.querySelectorAll('input[type="radio"], textarea').forEach(el => {
                        el.disabled = true;
                    });
                    Swal.fire({
                        icon: 'info',
                        title: 'Already Submitted',
                        text: 'You have already submitted evaluation for this teacher.',
                        timer: 3000
                    });
                } else {
                    // Enable all inputs
                    document.querySelectorAll('input[type="radio"], textarea').forEach(el => {
                        el.disabled = false;
                    });
                }

                // Load answers
                for (const qid of questionIds) {
                    const val = saved.answers[qid];
                    const radios = document.querySelectorAll(`input[name="q_${qid}"]`);
                    radios.forEach(r => {
                        r.checked = Number(r.value) === Number(val);
                        // Update button style
                        const label = r.closest('label');
                        if (label) {
                            if (r.checked) {
                                label.classList.add('active');
                            } else {
                                label.classList.remove('active');
                            }
                        }
                    });
                }

                // Load comments
                commentIds.forEach(id => {
                    const key = id.replace('c_', '');
                    document.getElementById(id).value = saved.comment[key] || '';
                });

                prevBtn.disabled = state.current === 0;
                const isLast = state.current === targets.length - 1;
                nextBtn.classList.toggle('d-none', isLast);
                finalSubmitBtn.classList.toggle('d-none', !isLast);
                currentStepInput.value = state.current;
            }

            // Style radio buttons on click
            document.querySelectorAll('.option-grid input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const name = this.name;
                    document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                        const label = r.closest('label');
                        if (label) {
                            if (r.checked) {
                                label.classList.add('active');
                            } else {
                                label.classList.remove('active');
                            }
                        }
                    });
                });
            });

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

            finalSubmitBtn.addEventListener('click', async () => {
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
                        text: 'Please fill all teacher evaluations before submit.'
                    });
                    state.current = firstMissingStep;
                    loadCurrentStep();
                    return;
                }

                // Confirm submission
                const result = await Swal.fire({
                    icon: 'question',
                    title: 'Confirm Submission',
                    text: 'Are you sure you want to submit all evaluations?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit',
                    cancelButtonText: 'Cancel'
                });

                if (!result.isConfirmed) return;

                const submissions = targets.map((target, idx) => {
                    const step = state.byStep[idx];
                    return {
                        teacher_id: target.teacher_id,
                        session_id: target.session_id,
                        class_id: target.class_id,
                        course_id: target.course_id,
                        answers: questionIds.map(qid => ({
                            question_id: qid,
                            option_id: step.answers[qid]
                        })),
                        comment: step.comment,
                    };
                });

                document.getElementById('submissions_payload').value = JSON.stringify(submissions);

                // Clear localStorage after successful submission
                localStorage.removeItem('teacherEvaluationState');

                document.getElementById('teacherEvaluationWizard').submit();
            });

            // Initialize
            (async () => {
                loadSavedData();
                state.submittedTargets = await checkSubmittedStatus();
                loadCurrentStep();
            })();
        })();

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Submitted',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear localStorage on successful submission
                localStorage.removeItem('teacherEvaluationState');
            });
        @endif
    </script>
@stop
