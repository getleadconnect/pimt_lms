<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\DescriptiveQuestionMark;
use App\Models\Course;
use App\Exports\ExamResultsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Student;
use App\Models\QuestionPaper;
use App\Models\TestResult;
use DataTables;
use Session;

/**
 * Controller for the Teacher area.
 *
 * Admins with role_id = 4 (Teacher) are redirected here after login by the
 * LoginController. All routes are protected by the `admin` auth guard and
 * additionally check role_id == 4.
 */
class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            $u = Auth::guard('admin')->user();
            if (!$u || (int) $u->role_id !== 4) {
                Session::flash('message', 'danger#You do not have access to the Teacher area.');
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }

    /**
     * Main Teacher dashboard (same visual layout as the student dashboard,
     * fed with dummy data for now).
     */
    public function dashboard()
    {
        $teacher = (object) [
            'name'        => Auth::guard('admin')->user()->name ?? 'Teacher',
            'email'       => Auth::guard('admin')->user()->email ?? '',
            'designation' => 'Senior Teacher',
        ];


        $course_count=Course::getTotalCourses();
        $students_count=Student::getTotalStudents();
        $qpaper_count=QuestionPaper::getTotalQuestionPapers();
        $attended_count=TestResult::getAttendedStudents();
        
        $stats = [
            (object) ['icon' => 'fa-book-open',   'color' => 'green',  'label' => 'Courses',       'value' => $course_count],
            (object) ['icon' => 'fa-users',       'color' => 'blue',   'label' => 'Total Students',   'value' => $students_count],
            (object) ['icon' => 'fa-file-alt',    'color' => 'amber',  'label' => 'Question Papers',  'value' => $qpaper_count],
            (object) ['icon' => 'fa-users',       'color' => 'violet', 'label' => 'Attended Students',  'value' => $attended_count],
        ];

           // ===== "Mock Tests" — list ALL active question papers along with a
        //       live count of attended students from test_results. =====



        $mockTests = DB::table('question_papers as qp')
            ->leftJoin('courses as c', 'c.id', '=', 'qp.course_id')
            ->leftJoin('test_results as tr', 'tr.question_paper_id', '=', 'qp.id')
            ->where('qp.status', 1)
            ->groupBy('qp.id', 'qp.question_paper_name', 'qp.start_date', 'qp.start_time', 'qp.end_time', 'c.course_name')
            ->orderBy('qp.start_date', 'desc')
            ->orderBy('qp.start_time', 'desc')
            ->take(10)
            ->get([
                'qp.id',
                'qp.question_paper_name as name',
                'qp.start_date',
                'qp.start_time',
                'qp.end_time',
                'c.course_name',
                DB::raw('COUNT(DISTINCT tr.student_id) as attended'),
            ]);

        return view('admin.teacher.dashboard', compact(
            'teacher', 'stats',  'mockTests'
        ));
    }

    /**
     * Show all students who attended a given question paper. Reads from
     * test_results filtered by question_paper_id, joined with students for
     * profile info.
     */
    public function attendedStudents($questionPaperId)
    {
        $paper = DB::table('question_papers as qp')
            ->leftJoin('courses as c', 'c.id', '=', 'qp.course_id')
            ->where('qp.id', $questionPaperId)
            ->select(
                'qp.id',
                'qp.question_paper_name',
                'qp.start_date',
                'qp.start_time',
                'qp.end_time',
                'qp.duration',
                'c.course_name'
            )
            ->first();

        if (!$paper) {
            Session::flash('message', 'danger#Question paper not found.');
            return redirect()->route('teacher.dashboard');
        }

        $attempts = DB::table('test_results as tr')
            ->leftJoin('students as s', 's.id', '=', 'tr.student_id')
            ->where('tr.question_paper_id', $questionPaperId)
            ->orderBy('tr.score', 'desc')
            ->orderBy('tr.created_at', 'desc')
            ->get([
                'tr.id',
                'tr.student_id',
                'tr.test_date',
                'tr.total_questions',
                'tr.answer  as correct',
                'tr.wrong',
                'tr.skipped',
                'tr.marks',
                'tr.negative',
                'tr.score',
                'tr.descriptive_mark',
                'tr.total_time',
                'tr.status',
                'tr.created_at',
                's.student_name',
                's.candidate_id',
                's.email',
                's.mobile',
            ]);

        return view('admin.teacher.attended_students', compact('paper', 'attempts'));
    }

    /**
     * AJAX (yajra DataTables) feed for the Attended Students table.
     * Returns one row per test_results entry for the given question_paper_id,
     * with the same columns + Evaluate cell shown on the page.
     */
    public function attendedStudentsData(Request $request, $questionPaperId)
    {
        $query = DB::table('test_results as tr')
            ->leftJoin('students as s', 's.id', '=', 'tr.student_id')
            ->where('tr.question_paper_id', $questionPaperId)
            ->select(
                'tr.id',
                'tr.student_id',
                'tr.test_date',
                'tr.total_questions',
                'tr.answer  as correct',
                'tr.wrong',
                'tr.skipped',
                'tr.score',
                'tr.descriptive_mark',
                'tr.status',
                'tr.created_at',
                's.student_name',
                's.candidate_id',
                's.email',
                's.mobile'
            );

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('test_date', function ($r) {
                $d = $r->test_date ?: $r->created_at;
                return $d ? \Carbon\Carbon::parse($d)->format('d M Y') : '-';
            })
            ->addColumn('student', function ($r) {
                $initial = strtoupper(substr($r->student_name ?? '?', 0, 1));
                $name = e($r->student_name ?? '—');
                $email = e($r->email ?? '');
                return '<span class="stu">'
                     . '<span class="avt">' . $initial . '</span>'
                     . '<span><span class="nm">' . $name . '</span>'
                     . '<span class="sub">' . $email . '</span></span></span>';
            })
            ->addColumn('correct_pill', function ($r) { return '<span class="pill-tag green">' . (int)$r->correct . '</span>'; })
            ->addColumn('wrong_pill',   function ($r) { return '<span class="pill-tag red">'   . (int)$r->wrong   . '</span>'; })
            ->addColumn('skipped_pill', function ($r) { return '<span class="pill-tag amber">' . (int)$r->skipped . '</span>'; })
            ->addColumn('desc_mark', function ($r) {
                $val = $r->descriptive_mark ?? 0;
                return '<span class="desc-mark-cell" id="desc-mark-' . (int)$r->student_id . '">' . $val . '</span>';
            })
            ->editColumn('score', function ($r) {
                return '<strong id="score-cell-' . (int)$r->student_id . '">' . $r->score . '</strong>';
            })
            ->addColumn('evaluate', function ($r) {
                $id = (int) $r->student_id;
                $name = e($r->student_name ?? '');
                if (!is_null($r->descriptive_mark)) {
                    $html = '<span class="pill-tag green"><i class="fa fa-check-circle"></i> Evaluated</span>';
                } else {
                    $html = '<button type="button" class="btn-evaluate" '
                          . 'data-student-id="' . $id . '" '
                          . 'data-student-name="' . $name . '">'
                          . '<i class="fa fa-edit"></i> Evaluate</button>';
                }
                return '<span id="evaluate-cell-' . $id . '">' . $html . '</span>';
            })
            ->rawColumns(['student', 'correct_pill', 'wrong_pill', 'skipped_pill', 'desc_mark', 'score', 'evaluate'])
            ->make(true);
    }

    /**
     * AJAX: return all descriptive (question_type = 2) questions for the
     * given paper along with the specified student's typed answer and any
     * mark already awarded.
     */
    public function getDescriptiveQuestions($questionPaperId, $studentId)
    {
        try {
            // Read from descriptive_question_marks (the student's grading
            // rows), joined with the corresponding questions text. Filter on
            // the descriptive_question_marks side using question_paper_id
            // and student_id. Aliased question_id so the front-end always
            // has the question id available regardless of the join column
            // collisions on `id`.
            $questions = DescriptiveQuestionMark::select(
                    'descriptive_question_marks.question_id',
                    'descriptive_question_marks.question_answer',
                    'descriptive_question_marks.mark',
                    'questions.question'
                )
                ->leftJoin('questions', 'descriptive_question_marks.question_id', '=', 'questions.id')
                ->where('descriptive_question_marks.question_paper_id', $questionPaperId)
                ->where('descriptive_question_marks.student_id', $studentId)
                ->orderBy('descriptive_question_marks.question_id', 'asc')
                ->get();

            return response()->json([
                'status'    => true,
                'questions' => $questions,
            ]);
        } catch (\Exception $e) {
            \Log::error('getDescriptiveQuestions error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * AJAX: persist the marks awarded for each descriptive question. Writes:
     *   - descriptive_question_marks.mark      (per question, insert/update)
     *   - test_results.descriptive_mark        (sum of all marks)
     *   - test_results.score                   (objective_score + descriptive total)
     */
    public function saveDescriptiveMarks(Request $request)
    {
        $request->validate([
            'question_paper_id' => 'required|integer',
            'student_id'        => 'required|integer',
            'marks'             => 'required|array',
            'marks.*.question_id' => 'required|integer',
            'marks.*.mark'        => 'nullable|numeric|min:0|max:3',
        ]);

        $paperId   = (int) $request->question_paper_id;
        $studentId = (int) $request->student_id;
        $marks     = $request->input('marks', []);

        DB::beginTransaction();
        try {
            $total = 0;

            foreach ($marks as $row) {
                $qid  = (int) $row['question_id'];
                $mark = isset($row['mark']) && $row['mark'] !== '' ? (float) $row['mark'] : 0;
                $total += $mark;

                // 1. descriptive_question_marks (insert/update per student+paper+question)
                $existing = DB::table('descriptive_question_marks')
                    ->where('student_id',        $studentId)
                    ->where('question_paper_id', $paperId)
                    ->where('question_id',       $qid)
                    ->first();

                if ($existing) {
                    DB::table('descriptive_question_marks')
                        ->where('id', $existing->id)
                        ->update(['mark' => $mark, 'updated_at' => now()]);
                } else {
                    DB::table('descriptive_question_marks')->insert([
                        'student_id'        => $studentId,
                        'question_paper_id' => $paperId,
                        'question_id'       => $qid,
                        'question_answer'   => null,
                        'mark'              => $mark,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);
                }

                }

            // 2. test_results — store the new descriptive_mark and update
            //    score = existing test_results.score + descriptive total.
            $tr = DB::table('test_results')
                ->where('student_id',        $studentId)
                ->where('question_paper_id', $paperId)
                ->first();

            if ($tr) {
                $newScore = ((float) ($tr->score ?? 0)) + $total;

                DB::table('test_results')
                    ->where('id', $tr->id)
                    ->update([
                        'descriptive_mark' => $total,
                        'score'            => $newScore,
                        'updated_at'       => now(),
                    ]);
            }

            DB::commit();
            return response()->json([
                'status'  => true,
                'message' => 'Descriptive marks saved successfully.',
                'total'   => $total,
                'score'   => isset($newScore) ? $newScore : null,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('saveDescriptiveMarks error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Stub — list courses this teacher is teaching.
     */
    public function myCourses()
    {
        return view('admin.teacher.my_courses');
    }

    /**
     * Stub — list students enrolled across this teacher's courses.
     */
    public function myStudents()
    {
        return view('admin.teacher.my_students');
    }

    /**
     * Stub — show question papers prepared by this teacher.
     */
    public function myQuestionPapers()
    {
        return view('admin.teacher.my_question_papers');
    }

    /**
     * Stub — teacher profile page.
     */
    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.teacher.profile', compact('admin'));
    }

    /**
     * Exam Tests page — renders the shell with filter controls. The actual
     * table rows are loaded over AJAX by yajra DataTables.
     */
    public function examTests()
    {
        $courses = Course::select('id', 'course_name')
            ->where('status', 1)
            ->orderBy('course_name', 'asc')
            ->get();

        return view('admin.teacher.exam_tests', compact('courses'));
    }

    /**
     * AJAX endpoint that feeds the Exam Tests DataTable. Supports filtering
     * by course_id and by a date range on the paper's start_date.
     */
    public function examTestsData(Request $request)
    {
        $courseId = $request->input('course_id');
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $query = DB::table('question_papers as qp')
            ->leftJoin('courses as c', 'c.id', '=', 'qp.course_id')
            ->leftJoin('test_results as tr', 'tr.question_paper_id', '=', 'qp.id')
            ->where('qp.status', 1)
            ->when($courseId, function ($q) use ($courseId) {
                $q->where('qp.course_id', $courseId);
            })
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->whereDate('qp.start_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                $q->whereDate('qp.start_date', '<=', $dateTo);
            })
            ->groupBy(
                'qp.id', 'qp.question_paper_name',
                'qp.start_date', 'qp.start_time', 'qp.end_time',
                'c.course_name'
            )
            ->select(
                'qp.id',
                'qp.question_paper_name as name',
                'qp.start_date',
                'qp.start_time',
                'qp.end_time',
                'c.course_name',
                DB::raw('COUNT(DISTINCT tr.student_id) as attended')
            );

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('start_date', function ($r) {
                return $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('d M Y') : '-';
            })
            ->addColumn('time_range', function ($r) {
                $s = $r->start_time ? \Carbon\Carbon::parse($r->start_time)->format('h:i A') : '-';
                $e = $r->end_time   ? \Carbon\Carbon::parse($r->end_time)->format('h:i A')   : '-';
                return $s . ' — ' . $e;
            })
            ->addColumn('status', function ($r) {
                // Mirror the dashboard's status rule.
                //   start > today                                  → Upcoming
                //   start < today                                  → Closed
                //   start == today && end_time > current_time      → Closed
                //   start == today && end_time <= current_time     → On Going
                if (empty($r->start_date)) {
                    return '<span class="pill-tag amber">—</span>';
                }
                $tz       = 'Asia/Kolkata';
                $now      = \Carbon\Carbon::now($tz);
                $today    = $now->format('Y-m-d');
                $start    = \Carbon\Carbon::parse($r->start_date)->format('Y-m-d');

                $examEnd  = (!empty($r->start_date) && !empty($r->end_time))
                    ? \Carbon\Carbon::parse($start . ' ' . $r->end_time, $tz)
                    : null;

                if ($start === $today) {
                    if ($examEnd && $examEnd->lt($now)) {
                        return '<span class="pill-tag red"><i class="fa fa-lock"></i> Closed</span>';
                    }
                    return '<span class="pill-tag green"><i class="fa fa-play"></i> On Going</span>';
                } elseif ($start < $today) {
                    return '<span class="pill-tag red"><i class="fa fa-lock"></i> Closed</span>';
                }
                return '<span class="pill-tag amber"><i class="fa fa-clock"></i> Upcoming</span>';
            })
            ->addColumn('action', function ($r) {
                // Mirror the dashboard's action rule.
                //   attended > 0 && exam_ended → View Students button
                //   exam_ended (no submissions) → "No Submissions" pill
                //   otherwise                   → "--"
                $tz      = 'Asia/Kolkata';
                $now     = \Carbon\Carbon::now($tz);
                $examEnd = (!empty($r->start_date) && !empty($r->end_time))
                    ? \Carbon\Carbon::parse(
                        \Carbon\Carbon::parse($r->start_date)->format('Y-m-d') . ' ' . $r->end_time,
                        $tz
                    )
                    : null;
                $examEnded = $examEnd && $now->gt($examEnd);

                if ((int)$r->attended !== 0 && $examEnded) {
                    $url = route('teacher.attended-students', $r->id);
                    return '<a href="' . $url . '" rel="noopener" class="btn-soft" title="View attended students">'
                         . '<i class="fa fa-eye"></i> View Students</a>';
                }
                if ($examEnded) {
                    return '<span class="pill-tag amber" title="Exam ended, no submissions">'
                         . '<i class="fa fa-info-circle"></i> No Submissions</span>';
                }
                return '--';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Exam Results page — renders the shell with course/paper filters.
     * Student rows are fetched by examResultsData via AJAX once the teacher
     * selects a course AND a question paper.
     */
    public function examResults()
    {
        $courses = Course::select('id', 'course_name')
            ->where('status', 1)
            ->orderBy('course_name', 'asc')
            ->get();

        return view('admin.teacher.exam_results', compact('courses'));
    }

    /**
     * AJAX: return question papers for a given course (powers the cascading
     * paper dropdown on the Exam Results page).
     */
    public function examResultsPapers($courseId)
    {
        $papers = DB::table('question_papers')
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->orderBy('start_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get(['id', 'question_paper_name', 'start_date']);

        return response()->json(['status' => true, 'papers' => $papers]);
    }

    /**
     * AJAX (DataTables): students who attended the selected question paper.
     * Mirrors the columns shown on the attended_students page.
     */
    public function examResultsData(Request $request)
    {
        $paperId = $request->input('question_paper_id');

        if (!$paperId) {
            return DataTables::of(collect())->make(true);
        }

        $query = DB::table('test_results as tr')
            ->leftJoin('students as s', 's.id', '=', 'tr.student_id')
            ->where('tr.question_paper_id', $paperId)
            ->whereNotNull('tr.descriptive_mark') // only evaluated students
            ->select(
                'tr.id',
                'tr.student_id',
                'tr.test_date',
                'tr.total_questions',
                'tr.answer  as correct',
                'tr.wrong',
                'tr.skipped',
                'tr.score',
                'tr.descriptive_mark',
                'tr.status',
                'tr.created_at',
                's.student_name',
                's.candidate_id',
                's.email',
                's.mobile',
                // Standard-ranking subquery: how many evaluated students for
                // the same paper have a STRICTLY higher score, plus 1.
                // Ties share a rank, the next rank skips appropriately.
                DB::raw('(SELECT COUNT(*) + 1
                          FROM test_results tr2
                          WHERE tr2.question_paper_id = tr.question_paper_id
                            AND tr2.descriptive_mark IS NOT NULL
                            AND tr2.score > tr.score) as rank_position')
            );

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('test_date', function ($r) {
                $d = $r->test_date ?: $r->created_at;
                return $d ? \Carbon\Carbon::parse($d)->format('d M Y') : '-';
            })
            ->addColumn('student', function ($r) {
                $initial = strtoupper(substr($r->student_name ?? '?', 0, 1));
                $name = e($r->student_name ?? '—');
                $email = e($r->email ?? '');
                return '<span class="stu">'
                     . '<span class="avt">' . $initial . '</span>'
                     . '<span><span class="nm">' . $name . '</span>'
                     . '<span class="sub">' . $email . '</span></span></span>';
            })
            ->addColumn('correct_pill', function ($r) {
                return '<span class="pill-tag green">' . (int)$r->correct . '</span>';
            })
            ->addColumn('wrong_pill', function ($r) {
                return '<span class="pill-tag red">' . (int)$r->wrong . '</span>';
            })
            ->addColumn('skipped_pill', function ($r) {
                return '<span class="pill-tag amber">' . (int)$r->skipped . '</span>';
            })
            ->addColumn('desc_mark', function ($r) {
                $val = $r->descriptive_mark ?? 0;
                return '<span class="desc-mark-cell" id="desc-mark-' . (int)$r->student_id . '">' . $val . '</span>';
            })
            ->editColumn('score', function ($r) {
                return '<strong id="score-cell-' . (int)$r->student_id . '">' . $r->score . '</strong>';
            })
            ->addColumn('rank', function ($r) {
                $rank = (int) ($r->rank_position ?? 0);
                $cls  = 'rank-pill';
                if      ($rank === 1) { $cls .= ' rank-gold';   $icon = '<i class="fa fa-trophy"></i> '; }
                else if ($rank === 2) { $cls .= ' rank-silver'; $icon = '<i class="fa fa-medal"></i> ';  }
                else if ($rank === 3) { $cls .= ' rank-bronze'; $icon = '<i class="fa fa-medal"></i> ';  }
                else                  { $icon = ''; }
                return '<span class="' . $cls . '">' . $icon . '#' . $rank . '</span>';
            })
            ->rawColumns(['student', 'correct_pill', 'wrong_pill', 'skipped_pill', 'desc_mark', 'score', 'rank'])
            ->make(true);
    }

    /**
     * Download the filtered Exam Results as CSV/XLSX (maatwebsite/excel).
     * Requires question_paper_id; same filters as the table.
     */
    public function exportExamResults(Request $request)
    {
        $paperId = (int) $request->input('question_paper_id');
        if (!$paperId) {
            Session::flash('message', 'danger#Please select a question paper before exporting.');
            return redirect()->route('teacher.exam-results');
        }

        $paper = DB::table('question_papers')->where('id', $paperId)->first(['question_paper_name']);
        $paperName = $paper ? $paper->question_paper_name : 'exam-results';

        // Build a safe filename
        $type     = strtolower($request->input('type', 'csv')); // 'csv' or 'xlsx'
        $ext      = ($type === 'xlsx') ? 'xlsx' : 'csv';
        $fmt      = ($ext === 'xlsx')  ? \Maatwebsite\Excel\Excel::XLSX : \Maatwebsite\Excel\Excel::CSV;
        $slug     = preg_replace('/[^A-Za-z0-9\-]+/', '-', $paperName);
        $filename = 'exam-results-' . $slug . '-' . date('Ymd-His') . '.' . $ext;

        return Excel::download(new ExamResultsExport($paperId, $paperName), $filename, $fmt);
    }

    /**
     * Attended Students landing page — same idea as Exam Results, but lists
     * all attempts (no descriptive_mark filter) and shows the Evaluate
     * column instead of Rank. Teacher picks a course + paper to load.
     */
    public function attendedStudentsList()
    {
        $courses = Course::select('id', 'course_name')
            ->where('status', 1)
            ->orderBy('course_name', 'asc')
            ->get();

        return view('admin.teacher.attended_students_list', compact('courses'));
    }

    /**
     * AJAX (DataTables) feed for the Attended Students landing page.
     * Same logic as attendedStudentsData but takes question_paper_id from
     * the query string so the page can swap the filter without reloading.
     */
    public function attendedStudentsListData(Request $request)
    {
        $paperId = $request->input('question_paper_id');
        if (!$paperId) {
            return DataTables::of(collect())->make(true);
        }
        // Reuse the existing per-paper implementation.
        return $this->attendedStudentsData($request, $paperId);
    }
}
