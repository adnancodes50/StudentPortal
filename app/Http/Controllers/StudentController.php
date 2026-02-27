<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use App\Models\DateSheet;
use App\Models\StudentClassesCoursesModel;
use App\Models\StudentModel;
use App\Models\TeacherClasses;
use App\Models\TimeTableMOdel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home', $this->resolveStudentDashboardData());
    }


    public function viewcources()
    {
        return view('admin.student-courses.index', $this->resolveStudentDashboardData());
    }

    public function timetable()
    {
        $data = $this->resolveStudentDashboardData();
        $assignedCourses = $data['assignedCourses'] ?? collect();
        $timeTable = collect();

        // dd($timeTable);

        if ($assignedCourses->isNotEmpty()) {
            $courseKeys = $assignedCourses
                ->map(function ($course) {
                    return [
                        'class_id' => (int) $course->class_id,
                        'course_id' => (int) $course->course_id,
                        'session_id' => (int) $course->session_id,
                        'section' => Str::lower(trim((string) ($course->student_section ?? ''))),
                    ];
                })
                ->unique(fn ($item) => implode('|', [$item['class_id'], $item['course_id'], $item['session_id'], $item['section']]))
                ->values();

            $timeTable = TimeTableMOdel::query()
                ->select([
                    'id',
                    'class_id',
                    'course_id',
                    'section',
                    'lec_no',
                    'room_id',
                    'day',
                    'session_id',
                    'time_from',
                    'time_to',
                ])
                ->with([
                    'class:class_id,class_name',
                    'course:course_id,course_code,course_title',
                    'room:room_id,room_no',
                    'session:session_id,session_type,session_year,session_timing',
                ])
                ->where(function ($query) use ($courseKeys) {
                    foreach ($courseKeys as $key) {
                        $query->orWhere(function ($courseQuery) use ($key) {
                            $courseQuery
                                ->where('class_id', $key['class_id'])
                                ->where('course_id', $key['course_id'])
                                ->where('session_id', $key['session_id']);

                            if ($key['section'] !== '') {
                                $courseQuery->where(function ($sectionQuery) use ($key) {
                                    $sectionQuery->whereRaw('LOWER(TRIM(section)) = ?', [$key['section']])
                                        ->orWhereNull('section')
                                        ->orWhere('section', '');
                                });
                            }
                        });
                    }
                })
                ->orderByRaw("FIELD(LOWER(day), 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
                ->orderBy('time_from')
                ->orderBy('lec_no')
                ->get();
        }

        // dd($timeTable);

        return view('admin.timetable.index', array_merge($data, [
            'timeTable' => $timeTable,
        ]));
    }

    public function attendence()
    {
        $data = $this->resolveStudentDashboardData();
        $assignedCourses = $data['assignedCourses'] ?? collect();
        $student = $data['student'] ?? null;
        $attendances = collect();
        $lectureNumbers = range(1, 32);
        $attendanceSummary = collect();

        if ($student && $assignedCourses->isNotEmpty()) {
            $courseKeys = $assignedCourses
                ->map(fn ($course) => [
                    'class_id' => (int) $course->class_id,
                    'course_id' => (int) $course->course_id,
                    'session_id' => (int) $course->session_id,
                    'section' => Str::lower(trim((string) ($course->student_section ?? ''))),
                ])
                ->unique(fn ($item) => implode('|', [$item['class_id'], $item['course_id'], $item['session_id'], $item['section']]))
                ->values();

            $attendances = AttendanceModel::query()
                ->select([
                    'student_attendance_id',
                    'student_id',
                    'class_id',
                    'course_id',
                    'session_id',
                    'section',
                    'lec_no',
                    'status',
                    'type_status',
                    'creation_date',
                    'created_by',
                ])
                ->with([
                    'class:class_id,class_name',
                    'course:course_id,course_code,course_title',
                    'session:session_id,session_type,session_year,session_timing',
                    'createdBy:id,name',
                ])
                ->where('student_id', (int) $student->student_id)
                ->where(function ($query) use ($courseKeys) {
                    foreach ($courseKeys as $key) {
                        $query->orWhere(function ($attendanceQuery) use ($key) {
                            $attendanceQuery
                                ->where('class_id', $key['class_id'])
                                ->where('course_id', $key['course_id'])
                                ->where('session_id', $key['session_id']);

                            if ($key['section'] !== '') {
                                $attendanceQuery->where(function ($sectionQuery) use ($key) {
                                    $sectionQuery->whereRaw('LOWER(TRIM(section)) = ?', [$key['section']])
                                        ->orWhereNull('section')
                                        ->orWhere('section', '');
                                });
                            }
                        });
                    }
                })
                ->orderByDesc('creation_date')
                ->orderByDesc('student_attendance_id')
                ->get();
        }

        if ($attendances->isNotEmpty()) {
            $attendanceSummary = $attendances
                ->groupBy(function ($item) {
                    return implode('|', [
                        (int) $item->class_id,
                        (int) $item->course_id,
                        (int) $item->session_id,
                        Str::lower(trim((string) ($item->section ?? ''))),
                    ]);
                })
                ->values()
                ->map(function ($group) use ($lectureNumbers) {
                    $first = $group->first();
                    $byLecture = $group
                        ->sortByDesc('creation_date')
                        ->sortByDesc('student_attendance_id')
                        ->groupBy(fn ($item) => (int) ($item->lec_no ?? 0))
                        ->map(fn ($items) => $items->first());

                    $lectureStatus = [];
                    $presentCount = 0;
                    $absentCount = 0;
                    $takenCount = 0;

                    foreach ($lectureNumbers as $lectureNo) {
                        $record = $byLecture->get($lectureNo);

                        if (! $record) {
                            $lectureStatus[$lectureNo] = null;
                            continue;
                        }

                        $status = (int) $record->status;
                        $isPresent = in_array($status, [1], true);
                        $isAbsent = in_array($status, [0, 2], true);

                        if ($isPresent) {
                            $lectureStatus[$lectureNo] = 'P';
                            $presentCount++;
                            $takenCount++;
                        } elseif ($isAbsent) {
                            $lectureStatus[$lectureNo] = 'A';
                            $absentCount++;
                            $takenCount++;
                        } else {
                            $lectureStatus[$lectureNo] = null;
                        }
                    }

                    $percentage = $takenCount > 0
                        ? (int) round(($presentCount / $takenCount) * 100)
                        : 0;

                    return [
                        'course' => $first->course,
                        'class' => $first->class,
                        'session' => $first->session,
                        'section' => $first->section,
                        'lecture_status' => $lectureStatus,
                        'taken_count' => $takenCount,
                        'present_count' => $presentCount,
                        'absent_count' => $absentCount,
                        'percentage' => $percentage,
                    ];
                })
                ->sortBy(fn ($item) => Str::lower((string) ($item['course']?->course_title ?? '')))
                ->values();
        }

        return view('admin.attendence.index', array_merge($data, [
            'attendances' => $attendances,
            'lectureNumbers' => $lectureNumbers,
            'attendanceSummary' => $attendanceSummary,
        ]));
    }

    public function datesheet()
    {
        $data = $this->resolveStudentDashboardData();

        // dd($data);
        return view('admin.datesheet.index', array_merge($data, [
            'dateSheets' => collect(),
            'selectedTerm' => null,
        ]));
    }

    public function profile()
    {
        return view('admin.profile.index', $this->resolveStudentDashboardData());
    }

    public function resetCredentials(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'old_username' => ['required', 'string', 'max:255'],
            'new_username' => [
                'required',
                'string',
                'max:255',
                'different:old_username',
                'unique:students_logins,student_login_name,' . $user->getKey() . ',student_login_id',
            ],
            'previous_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'different:previous_password', 'confirmed'],
        ]);

        $currentUsername = (string) ($user->student_login_name ?? '');

        if (! hash_equals($currentUsername, (string) $validated['old_username'])) {
            return back()->withErrors([
                'old_username' => 'Old username is incorrect.',
            ])->withInput();
        }

        $plainPassword = (string) $validated['previous_password'];
        $storedPassword = (string) ($user->student_login_password ?? '');
        $passwordMatches = hash_equals($storedPassword, $plainPassword);

        if (! $passwordMatches) {
            try {
                $passwordMatches = Hash::check($plainPassword, $storedPassword);
            } catch (RuntimeException $e) {
                $passwordMatches = false;
            }
        }

        if (! $passwordMatches) {
            return back()->withErrors([
                'previous_password' => 'Previous password is incorrect.',
            ])->withInput();
        }

        $user->student_login_name = $validated['new_username'];
        $user->student_login_password = $validated['new_password'];
        $user->updation_date = now();
        $user->save();

        return back()->with('status', 'Username and password updated successfully.');
    }

    public function datesheetData(Request $request)
    {
        // dd($request->all());
        $selectedTerm = $request->query('term');
        $selectedTerm = in_array((string) $selectedTerm, ['1', '2'], true) ? (int) $selectedTerm : null;

        if ($selectedTerm === null) {
            return response()->json([
                'rows' => [],
                'message' => 'Please select a term.',
            ]);
        }

        $data = $this->resolveStudentDashboardData();
        $assignedCourses = $data['assignedCourses'] ?? collect();

        if ($assignedCourses->isEmpty()) {
            return response()->json([
                'rows' => [],
                'message' => 'No courses are assigned yet.',
            ]);
        }

        $dateSheets = $this->resolveDateSheetsForAssignedCourses($assignedCourses, $selectedTerm);

        $rows = $dateSheets->values()->map(function ($sheet, $index) {
            $dateLabel = $sheet->date ? \Carbon\Carbon::parse($sheet->date)->format('d M Y') : 'N/A';
            $courseCode = $sheet->course->course_code ?? ($sheet->paper_code ?? 'N/A');
            $courseTitle = $sheet->course->course_title ?? ($sheet->paper_title ?? '');

            return [
                'sr' => $index + 1,
                'date' => $dateLabel,
                'course' => trim($courseCode . ($courseTitle !== '' ? ' - ' . $courseTitle : '')),
                'paper' => trim(($sheet->paper_code ?? 'N/A') . (! empty($sheet->paper_title) ? ' - ' . $sheet->paper_title : '')),
                'term' => $sheet->slip_term_label,
                'type' => $sheet->paper_type_label,
                'class' => $sheet->class->class_name ?? 'N/A',
                'section' => $sheet->class_section ?: 'N/A',
                'time' => ($sheet->time_from ?? 'N/A') . ' - ' . ($sheet->time_to ?? 'N/A'),
                'room' => $sheet->room ?? 'N/A',
            ];
        });

        return response()->json([
            'rows' => $rows,
            'message' => $rows->isEmpty() ? 'No date sheet found for selected term.' : null,
        ]);
    }

    private function resolveDateSheetsForAssignedCourses($assignedCourses, int $selectedTerm)
    {
        $courseKeys = $assignedCourses
            ->map(fn ($course) => [
                'class_id' => (int) $course->class_id,
                'course_id' => (int) $course->course_id,
                'session_id' => (int) $course->session_id,
                'section' => Str::lower(trim((string) ($course->student_section ?? ''))),
            ])
            ->unique(fn ($item) => implode('|', [$item['class_id'], $item['course_id'], $item['session_id'], $item['section']]))
            ->values();

        return DateSheet::query()
            ->select([
                'date_sheet_id',
                'session_id',
                'class_id',
                'class_section',
                'slip_term',
                'course_id',
                'paper_code',
                'paper_title',
                'paper_type',
                'date',
                'time_from',
                'time_to',
                'room',
            ])
            ->with([
                'class:class_id,class_name',
                'course:course_id,course_code,course_title',
                'session:session_id,session_type,session_year,session_timing',
            ])
            ->where(function ($query) use ($courseKeys) {
                foreach ($courseKeys as $key) {
                    $query->orWhere(function ($dateSheetQuery) use ($key) {
                        $dateSheetQuery
                            ->where('class_id', $key['class_id'])
                            ->where('course_id', $key['course_id'])
                            ->where('session_id', $key['session_id']);

                        if ($key['section'] !== '') {
                            $dateSheetQuery->where(function ($sectionQuery) use ($key) {
                                $sectionQuery->whereRaw('LOWER(TRIM(class_section)) = ?', [$key['section']])
                                    ->orWhereNull('class_section')
                                    ->orWhere('class_section', '');
                            });
                        }
                    });
                }
            })
            ->where('slip_term', $selectedTerm)
            ->orderBy('date')
            ->orderBy('time_from')
            ->orderBy('course_id')
            ->get();
    }

    private function resolveStudentDashboardData(): array
    {
        $studentLogin = Auth::user();
        $student = null;

        $assignedCourses = collect();
        $studentFullName = 'N/A';
        $registeredCoursesCount = 0;
        $currentClass = 'N/A';
        $currentSemester = 'N/A';
        $currentSession = 'N/A';
        $candidateStudentIds = [];

        if ($studentLogin) {
            if (! empty($studentLogin->student_id)) {
                $candidateStudentIds[] = (int) $studentLogin->student_id;
            }

            if (! empty($studentLogin->student_login_id)) {
                $candidateStudentIds[] = (int) $studentLogin->student_login_id;
            }

            $candidateStudentIds = array_values(array_unique(array_filter($candidateStudentIds)));

            // Always fetch student details from StudentModel.
            $student = StudentModel::query()
                ->select([
                    'student_id',
                    'student_first_name',
                    'student_last_name',
                    'arid_reg_no',
                    'student_cnic',
                    'student_gender',
                    'student_section',
                    'student_timing_shift',
                    'student_currunt_semester',
                    'student_current_session',
                    'student_joining_session',
                    'joining_session_id',
                    'primary_email',
                ])
                ->with([
                    'session:session_id,session_type,session_year,session_timing',
                    'joiningSession:session_id,session_type,session_year,session_timing',
                ])
                ->when(
                    ! empty($candidateStudentIds),
                    fn ($query) => $query->whereIn('student_id', $candidateStudentIds),
                    fn ($query) => $query->whereRaw('1 = 0')
                )
                ->first();

            // Fallback: if student_id mapping is wrong, try resolve by login name.
            if (! $student && ! empty($studentLogin->student_login_name)) {
                $loginToken = Str::of($studentLogin->student_login_name)
                    ->lower()
                    ->replaceMatches('/[^a-z]/', '')
                    ->toString();

                if ($loginToken !== '') {
                    $matchedStudent = StudentModel::query()
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
                        ->first();

                    if ($matchedStudent) {
                        $student = StudentModel::query()
                            ->select([
                                'student_id',
                                'student_first_name',
                                'student_last_name',
                                'arid_reg_no',
                                'student_cnic',
                                'student_gender',
                                'student_section',
                                'student_timing_shift',
                                'student_currunt_semester',
                                'student_current_session',
                                'student_joining_session',
                                'joining_session_id',
                                'primary_email',
                            ])
                            ->with([
                                'session:session_id,session_type,session_year,session_timing',
                                'joiningSession:session_id,session_type,session_year,session_timing',
                            ])
                            ->where('student_id', $matchedStudent->student_id)
                            ->first();
                    }
                }
            }

            if ($student && ! in_array((int) $student->student_id, $candidateStudentIds, true)) {
                $candidateStudentIds[] = (int) $student->student_id;
            }

            $assignedCourses = StudentClassesCoursesModel::query()
                ->select([
                    'students_classe_course_id',
                    'student_id',
                    'student_section',
                    'class_id',
                    'session_id',
                    'course_id',
                    'created_at',
                ])
                ->with([
                    'course' => fn ($query) => $query
                        ->select([
                            'course_id',
                            'course_code',
                            'course_title',
                            'course_credit_hours',
                            'created_by',
                        ])
                        ->with('createdBy:id,name,email'),
                    'class:class_id,class_name',
                    'session:session_id,session_type,session_year,session_timing',
                ])
                ->when(
                    ! empty($candidateStudentIds),
                    fn ($query) => $query->whereIn('student_id', $candidateStudentIds),
                    fn ($query) => $query->whereRaw('1 = 0')
                )
                ->orderByDesc('session_id')
                ->orderBy('class_id')
                ->orderBy('course_id')
                ->get();

            if ($assignedCourses->isNotEmpty()) {
                $teacherAssignments = TeacherClasses::query()
                    ->select([
                        'teacher_course_id',
                        'teacher_id',
                        'classs_id',
                        'course_id',
                        'session_id',
                        'class_section',
                    ])
                    ->with('teacher:teacher_id,teacher_first_name,teacher_last_name,email')
                    ->whereIn('classs_id', $assignedCourses->pluck('class_id')->filter()->unique()->values()->all())
                    ->whereIn('course_id', $assignedCourses->pluck('course_id')->filter()->unique()->values()->all())
                    ->whereIn('session_id', $assignedCourses->pluck('session_id')->filter()->unique()->values()->all())
                    ->get();

                $teacherByExactKey = $teacherAssignments->keyBy(function ($item) {
                    return implode('|', [
                        (int) $item->classs_id,
                        (int) $item->course_id,
                        (int) $item->session_id,
                        Str::lower(trim((string) $item->class_section)),
                    ]);
                });

                $teacherByBaseKey = $teacherAssignments->keyBy(function ($item) {
                    return implode('|', [
                        (int) $item->classs_id,
                        (int) $item->course_id,
                        (int) $item->session_id,
                    ]);
                });

                $assignedCourses = $assignedCourses->map(function ($course) use ($teacherByExactKey, $teacherByBaseKey) {
                    $baseKey = implode('|', [
                        (int) $course->class_id,
                        (int) $course->course_id,
                        (int) $course->session_id,
                    ]);

                    $exactKey = $baseKey . '|' . Str::lower(trim((string) ($course->student_section ?? '')));

                    $teacherAssignment = $teacherByExactKey->get($exactKey) ?? $teacherByBaseKey->get($baseKey);

                    $course->resolved_teacher_name = $teacherAssignment?->teacher?->teacher_name ?? 'Not Found';
                    $course->resolved_teacher_email = $teacherAssignment?->teacher?->email ?? 'Not Found';

                    return $course;
                });
            }
        }

        $studentFullName = trim(($student->student_first_name ?? '') . ' ' . ($student->student_last_name ?? '')) ?: 'N/A';

        if ($studentLogin && $studentFullName !== 'N/A') {
            // Feed AdminLTE user menu with the resolved student full name.
            $studentLogin->name = $studentFullName;
        }

        $registeredCoursesCount = $assignedCourses->count();
        $currentClass = $assignedCourses->first()?->class?->class_name ?? 'N/A';
        $currentSemester = $student->student_currunt_semester ?? 'N/A';
        $currentSession = $student?->joiningSession
            ? trim(($student->joiningSession->session_type ?? '') . ' ' . ($student->joiningSession->session_year ?? '') . ' ' . ($student->joiningSession->session_timing ?? ''))
            : ($student?->student_joining_session ?? 'N/A');

        return compact(
            'student',
            'studentLogin',
            'assignedCourses',
            'studentFullName',
            'registeredCoursesCount',
            'currentClass',
            'currentSemester',
            'currentSession'
        );
    }
}
