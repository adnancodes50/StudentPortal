<?php

namespace App\Http\Controllers;

use App\Models\CourseEvaluationComment;
use App\Models\CourseEvaluationQuestion;
use App\Models\CourseEvaluationReport;
use App\Models\StudentClassesCoursesModel;
use App\Models\StudentLogIn;
use App\Models\StudentModel;
use App\Models\TeacherClasses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CourcesEvalutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $questions = CourseEvaluationQuestion::query()
            ->orderBy('evaluation_question_id')
            ->get(['evaluation_question_id', 'evaluation_question_title']);

        $candidateStudentIds = $this->resolveCandidateStudentIds();

        $assignedCourses = $this->loadAssignedCourses($candidateStudentIds);

        if ($assignedCourses->isEmpty()) {
            $fallbackStudentIds = $this->resolveStudentIdsFromLoginName();
            if ($fallbackStudentIds->isNotEmpty()) {
                $candidateStudentIds = $candidateStudentIds->merge($fallbackStudentIds)->unique()->values();
                $assignedCourses = $this->loadAssignedCourses($candidateStudentIds);
            }
        }

        $teacherAssignments = collect();
        if ($assignedCourses->isNotEmpty()) {
            $teacherClassColumn = Schema::hasColumn('teachers_classes_courses', 'classs_id') ? 'classs_id' : 'class_id';
            $teacherSectionColumn = Schema::hasColumn('teachers_classes_courses', 'class_section')
                ? 'class_section'
                : (Schema::hasColumn('teachers_classes_courses', 'class_secction') ? 'class_secction' : null);

            $teacherSelect = [
                'teacher_course_id',
                'teacher_id',
                DB::raw($teacherClassColumn . ' as teacher_class_id'),
                'course_id',
                'session_id',
            ];

            if ($teacherSectionColumn) {
                $teacherSelect[] = DB::raw($teacherSectionColumn . ' as teacher_section');
            }

            $teacherAssignments = TeacherClasses::query()
                ->select($teacherSelect)
                ->with('teacher:teacher_id,teacher_first_name,teacher_last_name')
                ->whereIn($teacherClassColumn, $assignedCourses->pluck('class_id')->filter()->unique()->values()->all())
                ->whereIn('course_id', $assignedCourses->pluck('course_id')->filter()->unique()->values()->all())
                ->whereIn('session_id', $assignedCourses->pluck('session_id')->filter()->unique()->values()->all())
                ->get();
        }

        $teacherByExactKey = $teacherAssignments->keyBy(function ($item) {
            return implode('|', [
                (int) $item->teacher_class_id,
                (int) $item->course_id,
                (int) $item->session_id,
                Str::lower(trim((string) ($item->teacher_section ?? ''))),
            ]);
        });

        $teacherByBaseKey = $teacherAssignments->keyBy(function ($item) {
            return implode('|', [
                (int) $item->teacher_class_id,
                (int) $item->course_id,
                (int) $item->session_id,
            ]);
        });

        $evaluationTargets = $assignedCourses
            ->map(function ($course) use ($teacherByExactKey, $teacherByBaseKey) {
                $baseKey = implode('|', [
                    (int) $course->class_id,
                    (int) $course->course_id,
                    (int) $course->session_id,
                ]);
                $exactKey = $baseKey . '|' . Str::lower(trim((string) ($course->student_section ?? '')));

                $teacherAssignment = $teacherByExactKey->get($exactKey) ?? $teacherByBaseKey->get($baseKey);

                $sessionLabel = $course->session
                    ? trim(($course->session->session_type ?? '') . ' ' . ($course->session->session_year ?? '') . ' ' . ($course->session->session_timing ?? ''))
                    : ('Session #' . $course->session_id);

                $teacherName = $teacherAssignment?->teacher?->teacher_name ?? 'Teacher Not Found';
                $teacherId = (int) ($teacherAssignment?->teacher_id ?? 0); // keep 0 if not found, so dropdown still shows row

                return [
                    'assignment_id' => (int) $course->students_classe_course_id,
                    'teacher_id' => $teacherId,
                    'teacher_name' => $teacherName,
                    'session_id' => (int) $course->session_id,
                    'session_name' => $sessionLabel,
                    'class_id' => (int) $course->class_id,
                    'class_name' => $course->class->class_name ?? ('Class #' . $course->class_id),
                    'course_id' => (int) $course->course_id,
                    'course_name' => $course->course->course_title ?? ('Course #' . $course->course_id),
                    'label' => trim(
                        ($course->course->course_title ?? ('Course #' . $course->course_id))
                        . ' | '
                        . ($course->class->class_name ?? ('Class #' . $course->class_id))
                        . ' | '
                        . $sessionLabel
                        . ' | '
                        . $teacherName
                    ),
                ];
            })
            ->filter(fn ($target) => (int) $target['teacher_id'] > 0)
            ->values();
// dd($studentId );
        return view('admin.cources-evalution.index', [
            'questions' => $questions,
            'evaluationTargets' => $evaluationTargets,
        ]);
    }

    public function submit(Request $request)
    {
        if ($request->boolean('step_save')) {
            return $this->saveStep($request);
        }

        if ($request->filled('submissions_payload')) {
            return $this->submitBatch($request);
        }

        $validated = $request->validate([
            'teacher_id' => ['required', 'integer', 'min:1'],
            'session_id' => ['required', 'integer'],
            'class_id' => ['required', 'integer'],
            'course_id' => ['required', 'integer'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer'],
            'answers.*.option_id' => ['required', 'integer'],
            'answers.*.selected_option_id' => ['nullable', 'integer'],
            'comment.course_best_features' => ['nullable', 'string'],
            'comment.course_improvement_sgtions' => ['nullable', 'string'],
            'comment.course_content_organization' => ['nullable', 'string'],
            'comment.student_contribution' => ['nullable', 'string'],
            'comment.learning_environment' => ['nullable', 'string'],
            'comment.learning_resources' => ['nullable', 'string'],
            'comment.delivery_quality' => ['nullable', 'string'],
            'comment.assessment_ethodology' => ['nullable', 'string'],
        ]);

        $studentId = $this->resolveLoggedInStudentId($validated);


        if ($studentId <= 0) {
            return back()->withErrors([
                'student_id' => 'Unable to resolve logged-in student id.',
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $studentId) {
            $this->persistSubmission($validated, $studentId);
        });

        return back()->with('success', 'Course evaluation submitted successfully.');
    }

    private function saveStep(Request $request)
    {
        $payload = json_decode((string) $request->input('step_payload'), true);

        if (! is_array($payload)) {
            return response()->json(['message' => 'Invalid step payload.'], 422);
        }

        $validated = validator($payload, [
            'teacher_id' => ['required', 'integer', 'min:1'],
            'session_id' => ['required', 'integer'],
            'class_id' => ['required', 'integer'],
            'course_id' => ['required', 'integer'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer'],
            'answers.*.option_id' => ['required', 'integer'],
            'comment' => ['nullable', 'array'],
        ])->validate();

        $studentId = $this->resolveLoggedInStudentId($validated);
        if ($studentId <= 0) {
            return response()->json(['message' => 'Unable to resolve logged-in student id.'], 422);
        }

        DB::transaction(function () use ($validated, $studentId) {
            $this->persistSubmission($validated, $studentId);
        });

        return response()->json(['ok' => true]);
    }

    private function submitBatch(Request $request)
    {
        $decoded = json_decode((string) $request->input('submissions_payload'), true);
        $requiredQuestionCount = CourseEvaluationQuestion::query()->count();

        if (! is_array($decoded) || empty($decoded)) {
            return back()->withErrors([
                'submissions_payload' => 'Invalid evaluation payload.',
            ])->withInput();
        }

        foreach ($decoded as $submission) {
            if (
                ! is_array($submission)
                || ! isset($submission['answers'])
                || ! is_array($submission['answers'])
                || count($submission['answers']) < $requiredQuestionCount
            ) {
                return back()->withErrors([
                    'submissions_payload' => 'Please fill all courses evaluation before submit.',
                ])->withInput();
            }

            foreach ($submission['answers'] as $answer) {
                $questionId = (int) ($answer['question_id'] ?? 0);
                $optionId = (int) ($answer['option_id'] ?? 0);

                if ($questionId <= 0 || $optionId <= 0) {
                    return back()->withErrors([
                        'submissions_payload' => 'Please fill all courses evaluation before submit.',
                    ])->withInput();
                }
            }
        }

        DB::transaction(function () use ($decoded) {
            foreach ($decoded as $submission) {
                if (
                    ! is_array($submission)
                    || ! isset($submission['teacher_id'], $submission['session_id'], $submission['class_id'], $submission['course_id'], $submission['answers'])
                    || ! is_array($submission['answers'])
                    || empty($submission['answers'])
                ) {
                    continue;
                }

                $studentId = $this->resolveLoggedInStudentId([
                    'class_id' => (int) $submission['class_id'],
                    'course_id' => (int) $submission['course_id'],
                    'session_id' => (int) $submission['session_id'],
                ]);

                if ($studentId <= 0) {
                    continue;
                }

                $this->persistSubmission($submission, $studentId);
            }
        });

        return back()->with('success', 'Course evaluations submitted successfully.');
    }

    private function persistSubmission(array $submission, int $studentId): void
    {
        $teacherId = (int) $submission['teacher_id'];
        $sessionId = (int) $submission['session_id'];
        $classId = (int) $submission['class_id'];
        $courseId = (int) $submission['course_id'];

        CourseEvaluationReport::query()
            ->where('student_id', $studentId)
            ->where('teacher_id', $teacherId)
            ->where('session_id', $sessionId)
            ->where('classs_id', $classId)
            ->where('course_id', $courseId)
            ->delete();

        foreach ($submission['answers'] as $answer) {
            $questionId = (int) ($answer['question_id'] ?? 0);
            $optionId = (int) ($answer['option_id'] ?? ($answer['selected_option_id'] ?? 0));
            if ($questionId <= 0 || $optionId <= 0) {
                continue;
            }

            CourseEvaluationReport::create([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'session_id' => $sessionId,
                'classs_id' => $classId,
                'course_id' => $courseId,
                'question_id' => $questionId,
                'option_id' => $optionId,
            ]);
        }

        CourseEvaluationComment::query()
            ->where('student_id', $studentId)
            ->where('teacher_id', $teacherId)
            ->where('session_id', $sessionId)
            ->where('class_id', $classId)
            ->where('course_id', $courseId)
            ->delete();

        $comment = is_array($submission['comment'] ?? null) ? $submission['comment'] : [];
        $hasCommentData = collect($comment)->filter(fn ($value) => trim((string) $value) !== '')->isNotEmpty();

        if ($hasCommentData) {
            CourseEvaluationComment::create([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'session_id' => $sessionId,
                'class_id' => $classId,
                'course_id' => $courseId,
                'course_best_features' => $comment['course_best_features'] ?? null,
                'course_improvement_sgtions' => $comment['course_improvement_sgtions'] ?? null,
                'course_content_organization' => $comment['course_content_organization'] ?? null,
                'student_contribution' => $comment['student_contribution'] ?? null,
                'learning_environment' => $comment['learning_environment'] ?? null,
                'learning_resources' => $comment['learning_resources'] ?? null,
                'delivery_quality' => $comment['delivery_quality'] ?? null,
                'assessment_ethodology' => $comment['assessment_ethodology'] ?? null,
            ]);
        }
    }

    private function resolveCandidateStudentIds()
    {
        $user = Auth::user();

        $candidateStudentIds = collect([
            $user?->student_id ?? null,
            $user?->student_login_id ?? null,
        ])
            ->filter(fn ($id) => ! empty($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if (Auth::id()) {
            $mappedStudentId = (int) (StudentLogIn::query()
                ->where('student_login_id', (int) Auth::id())
                ->value('student_id') ?? 0);

            if ($mappedStudentId > 0) {
                $candidateStudentIds->push($mappedStudentId);
            }
        }

        if ($candidateStudentIds->isEmpty()) {
            $candidateStudentIds = $candidateStudentIds
                ->merge($this->resolveStudentIdsFromLoginName())
                ->unique()
                ->values();
        }

        return $candidateStudentIds->unique()->values();
    }

    private function resolveLoggedInStudentId(array $validated = []): int
    {
        $candidateIds = $this->resolveCandidateStudentIds();

        if ($candidateIds->isEmpty()) {
            return 0;
        }

        if (! empty($validated)) {
            $matchedStudentId = (int) (StudentClassesCoursesModel::query()
                ->whereIn('student_id', $candidateIds->all())
                ->where('class_id', (int) $validated['class_id'])
                ->where('course_id', (int) $validated['course_id'])
                ->where('session_id', (int) $validated['session_id'])
                ->value('student_id') ?? 0);

            if ($matchedStudentId > 0) {
                return $matchedStudentId;
            }
        }

        if ($candidateIds->count() === 1) {
            return (int) $candidateIds->first();
        }

        $bestStudentId = (int) (StudentClassesCoursesModel::query()
            ->select('student_id', DB::raw('COUNT(*) as courses_count'))
            ->whereIn('student_id', $candidateIds->all())
            ->groupBy('student_id')
            ->orderByDesc('courses_count')
            ->value('student_id') ?? 0);

        if ($bestStudentId > 0) {
            return $bestStudentId;
        }

        return $bestStudentId > 0 ? $bestStudentId : 0;
    }

    private function resolveStudentIdsFromLoginName()
    {
        $user = Auth::user();
        if (empty($user?->student_login_name)) {
            return collect();
        }

        $loginToken = Str::of($user->student_login_name)
            ->lower()
            ->replaceMatches('/[^a-z]/', '')
            ->toString();

        if ($loginToken === '') {
            return collect();
        }

        $matchedStudentIds = StudentModel::query()
            ->select([
                'students.student_id',
                DB::raw('COUNT(scc.students_classe_course_id) as courses_count'),
            ])
            ->leftJoin('students_classes_courses as scc', 'scc.student_id', '=', 'students.student_id')
            ->where(function ($query) use ($loginToken) {
                $query->whereRaw('LOWER(students.student_first_name) LIKE ?', ["%{$loginToken}%"])
                    ->orWhereRaw('LOWER(students.student_last_name) LIKE ?', ["%{$loginToken}%"])
                    ->orWhereRaw("LOWER(CONCAT(students.student_first_name, ' ', students.student_last_name)) LIKE ?", ["%{$loginToken}%"]);
            })
            ->groupBy('students.student_id')
            ->orderByDesc('courses_count')
            ->pluck('students.student_id')
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->values();

        return $matchedStudentIds;
    }

    private function loadAssignedCourses($studentIds)
    {
        return StudentClassesCoursesModel::query()
            ->select([
                'students_classe_course_id',
                'student_id',
                'student_section',
                'class_id',
                'session_id',
                'course_id',
            ])
            ->with([
                'course:course_id,course_code,course_title',
                'class:class_id,class_name',
                'session:session_id,session_type,session_year,session_timing',
            ])
            ->when(
                collect($studentIds)->isNotEmpty(),
                fn ($query) => $query->whereIn('student_id', collect($studentIds)->values()->all()),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->orderByDesc('session_id')
            ->orderBy('class_id')
            ->orderBy('course_id')
            ->get();
    }
}
