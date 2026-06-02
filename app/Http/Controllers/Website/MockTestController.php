<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TestResult;
use App\Models\DescriptiveQuestionMark;
use Session;
use DB;

class MockTestController extends Controller
{
    /**
     * Validate test window against current IST time.
     * Uses the paper's start_date + start_time / end_time. If start_date is
     * empty, today's IST date is used.
     */
    public function validateTestTime($questionPaperId)
    {
        try {
            $paper = DB::table('question_papers')
                    ->select('id', 'question_paper_name', 'start_date', 'start_time', 'end_time', 'status')
                    ->where('id', $questionPaperId)
                    ->first();

            if (!$paper || $paper->status != 1) {
                return response()->json([
                    'status' => 'invalid',
                    'message' => 'Question paper not found.',
                ], 404);
            }

            // Already attended by this student? Check test_results by
            // student_id + question_paper_id and block re-attempt.
            $user = Auth::guard('student')->user();
            if ($user) {
                $alreadyAttended = DB::table('test_results')
                    ->where('student_id', $user->student_id)
                    ->where('question_paper_id', $paper->id)
                    ->exists();

                if ($alreadyAttended) {
                    return response()->json([
                        'status'  => 'attended',
                        'message' => 'You have already attended this test. You cannot attempt it again.',
                    ]);
                }
            }

            $tz = 'Asia/Kolkata';
            $nowIst = \Carbon\Carbon::now($tz);

            $dateOnly = !empty($paper->start_date)
                ? \Carbon\Carbon::parse($paper->start_date)->format('Y-m-d')
                : $nowIst->format('Y-m-d');

            $startAt = !empty($paper->start_time)
                ? \Carbon\Carbon::parse($dateOnly . ' ' . $paper->start_time, $tz)
                : null;
            $endAt = !empty($paper->end_time)
                ? \Carbon\Carbon::parse($dateOnly . ' ' . $paper->end_time, $tz)
                : null;
                

            $startDisplay = $startAt ? $startAt->format('d M Y, h:i A') : null;
            $endDisplay   = $endAt   ? $endAt->format('d M Y, h:i A')   : null;
            $nowDisplay   = $nowIst->format('d M Y, h:i A');

            if ($startAt && $nowIst->lt($startAt)) {
                return response()->json([
                    'status'        => 'not_started',
                    'message'       => 'The test will start on ' . $startDisplay . ' (IST). Please wait until then.',
                    'start_display' => $startDisplay,
                    'end_display'   => $endDisplay,
                    'now_display'   => $nowDisplay,
                ]);
            }

            if ($endAt && $nowIst->gt($endAt)) {
                return response()->json([
                    'status'        => 'ended',
                    'message'       => 'The test ended on ' . $endDisplay . ' (IST). You can no longer start this test.',
                    'start_display' => $startDisplay,
                    'end_display'   => $endDisplay,
                    'now_display'   => $nowDisplay,
                ]);
            }

            return response()->json([
                'status'        => 'ok',
                'message'       => 'Test is available. Good luck!',
                'start_display' => $startDisplay,
                'end_display'   => $endDisplay,
                'now_display'   => $nowDisplay,
            ]);
        } catch (\Exception $e) {
            \Log::error('Validate test time error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Unable to validate test time. Please try again.',
            ], 500);
        }
    }
    

    public function takeTest($questionPaperId)
    {
        try {

            $user = Auth::guard('student')->user();

            // Get question paper details
            $questionPaper = DB::table('question_papers')
                            ->where('id', $questionPaperId)
                            ->where('status', 1)
                            ->whereDate('start_date',date('Y-m-d'))
                            ->first();

            if (!$questionPaper) {
                Session::flash('message', 'danger#Question paper not found.');
                return redirect()->route('student.course-mock-test');
            }

            // If this student already attended this paper, block re-attempt.
            // Show a SweetAlert on the take-test screen; OK redirects to the
            // student dashboard.
            $alreadyAttended = DB::table('test_results')
                ->where('student_id', $user->student_id)
                ->where('question_paper_id', $questionPaper->id)
                ->exists();

            if ($alreadyAttended) {
                return view('website.student.test-blocked', [
                    'icon'        => 'info',
                    'title'       => 'Already Attended',
                    'message'     => 'You have already attended this test. You cannot attempt it again.',
                    'redirectUrl' => route('student.dashboard'),
                ]);
            }

            // Enforce test window against IST server time (defence-in-depth in
            // case the client-side check is bypassed).
            $tz = 'Asia/Kolkata';
            $nowIst = \Carbon\Carbon::now($tz);

            $dateOnly = !empty($questionPaper->start_date)
                ? \Carbon\Carbon::parse($questionPaper->start_date)->format('Y-m-d')
                : $nowIst->format('Y-m-d');

            $startAt = !empty($questionPaper->start_time)
                ? \Carbon\Carbon::parse($dateOnly . ' ' . $questionPaper->start_time, $tz)
                : null;
            $endAt = !empty($questionPaper->end_time)
                ? \Carbon\Carbon::parse($dateOnly . ' ' . $questionPaper->end_time, $tz)
                : null;

            if ($startAt && $nowIst->lt($startAt)) {
                Session::flash('message', 'warning#The test will start on ' . $startAt->format('d M Y, h:i A') . ' (IST). Please wait until then.');
                return redirect()->route('student.course-mock-test');
            }

            if ($endAt && $nowIst->gt($endAt)) {
                Session::flash('message', 'danger#The test ended on ' . $endAt->format('d M Y, h:i A') . ' (IST). You can no longer start this test.');
                return redirect()->route('student.course-mock-test');
            }

            // Get all questions for this question paper
            $questions = DB::table('questions')
                        ->where('question_paper_id', $questionPaperId)
                        ->orderBy('id', 'ASC')
                        ->get();

            if ($questions->isEmpty()) {
                Session::flash('message', 'danger#No questions found for this test.');
                return redirect()->route('student.course-mock-test');
            }

            // Check if student has already attempted this test
            $existingAttempt = DB::table('test_results')
                              ->where('student_id', $user->student_id)
                              ->where('question_paper_id', $questionPaperId)
                              ->first();

            // Initialize or retrieve answers from session
            if (!session()->has('test_answers_' . $questionPaperId)) {
                session(['test_answers_' . $questionPaperId => []]);
                session(['test_start_time_' . $questionPaperId => now()]);
            }

            // Timer rule:
            //   effective_start = (start_time < current_time) ? current_time : start_time
            //   countdown       = end_time - effective_start
            // i.e. if the scheduled start has already passed, the system's
            // current IST time becomes the effective start; otherwise the
            // scheduled start is used. Falls back to duration-based countdown
            // if end_time is not set on the paper.
            if ($endAt) {
                $effectiveStartIst = ($startAt && $nowIst->lt($startAt)) ? $startAt : $nowIst;
                $remainingSeconds  = $effectiveStartIst->lt($endAt)
                    ? (int) $effectiveStartIst->diffInSeconds($endAt)
                    : 0;
            } else {
                $startTime = session('test_start_time_' . $questionPaperId);
                $elapsedSeconds = $startTime ? $startTime->diffInSeconds(now()) : 0;
                $remainingSeconds = max(0, ((int) $questionPaper->duration) * 60 - (int) $elapsedSeconds);
            }
            $remainingMinutes = (int) ceil($remainingSeconds / 60); // back-compat for any view code still using it

            $startTimeDisplay = $startAt ? $startAt->format('h:i A') : null;
            $endTimeDisplay   = $endAt   ? $endAt->format('h:i A')   : null;

            return view('website.student.take-test', compact(
                'questionPaper',
                'questions',
                'existingAttempt',
                'remainingMinutes',
                'remainingSeconds',
                'startTimeDisplay',
                'endTimeDisplay'
            ));

        } catch (\Exception $e) {
            \Log::error('Take test error: ' . $e->getMessage());
            Session::flash('message', 'danger#Error loading test. Please try again.');
            return redirect()->route('student.course-mock-test');
        }
    }

    public function submitAnswer(Request $request)
    {
        try {
            $questionPaperId = $request->question_paper_id;
            $questionId = $request->question_id;
            $answer = $request->answer; // Can be null for skipped

            // Get current answers from session
            $sessionKey = 'test_answers_' . $questionPaperId;
            $answers = session($sessionKey, []);

            // Update answer for this question
            $answers[$questionId] = $answer;

            // Save back to session
            session([$sessionKey => $answers]);

            return response()->json([
                'success' => true,
                'message' => 'Answer saved'
            ]);

        } catch (\Exception $e) {
            \Log::error('Submit answer error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving answer'
            ], 500);
        }
    }

    public function finishTest(Request $request)
    {
        try {
            $user = Auth::guard('student')->user();
            $questionPaperId = $request->question_paper_id;

            DB::beginTransaction();

            // Record test attendance
            DB::table('qpaper_attended')->insert([
                'student_id' => $user->student_id,
                'question_paper_id' => $questionPaperId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Delete previous test results for this student and question paper
            DB::table('test_all_results')
                ->where('student_id', $user->student_id)
                ->where('question_paper_id', $questionPaperId)
                ->delete();

            DB::table('test_results')
                ->where('student_id', $user->student_id)
                ->where('question_paper_id', $questionPaperId)
                ->delete();

            // Clear previous descriptive answers for this student + paper
            // (so a re-attempt overwrites prior descriptive submissions)
            DescriptiveQuestionMark::where('student_id', $user->student_id)
                                    ->where('question_paper_id', $questionPaperId)
                                    ->delete();

            // Get answers from session
            $sessionKey = 'test_answers_' . $questionPaperId;
            $answers = session($sessionKey, []);

            // Get start time
            $startTime = session('test_start_time_' . $questionPaperId);
            $endTime = now();
            $totalTime = $startTime ? $startTime->diffInMinutes($endTime) : 0;

            // Get all questions with correct answers
            $questions = DB::table('questions')
                        ->where('question_paper_id', $questionPaperId)
                        ->get();

            $objectiveTotal = 0;
            $correctCount = 0;
            $wrongCount = 0;
            $skippedCount = 0;
            $score = 0;

            // Process each question
            foreach ($questions as $question) {
                $userAnswer = isset($answers[$question->id]) ? $answers[$question->id] : null;

                // Descriptive (question_type = 2) — store text answer separately, do NOT score
                if ($question->question_type == 2) {
                    DescriptiveQuestionMark::create([
                        'student_id'        => $user->student_id,
                        'question_paper_id' => $questionPaperId,
                        'question_id'       => $question->id,
                        'question_answer'   => is_string($userAnswer) ? $userAnswer : '',
                        'mark'              => null, // To be set by admin during review
                    ]);
                    continue; // skip score / test_all_results
                }

                // Objective + image questions — existing scoring logic
                $objectiveTotal++;
                $isWrong = false;
                $isSkipped = false;

                if ($userAnswer === null || $userAnswer === '') {
                    $isSkipped = true;
                    $skippedCount++;
                } else if ($userAnswer == $question->correct_answer) {
                    $correctCount++;
                    $score += 1; // 1 mark per question
                } else {
                    $isWrong = true;
                    $wrongCount++;
                }

                DB::table('test_all_results')->insert([
                    'qbank_subject_id' => $question->qbank_subject_id,
                    'question_paper_id' => $questionPaperId,
                    'student_id' => $user->student_id,
                    'question_id' => $question->id,
                    'correct_answer' => $question->correct_answer,
                    'answer' => $userAnswer,
                    'wrong_status' => $isWrong ? 1 : 0,
                    'skipped_status' => $isSkipped ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Save summary to test_results (objective only — descriptive graded later)
            $testResultId = DB::table('test_results')->insertGetId([
                'subject_id' => $questions->first()->qbank_subject_id ?? null,
                'question_paper_id' => $questionPaperId,
                'student_id' => $user->student_id,
                'test_date' => now()->toDateString(),
                'total_questions' => $objectiveTotal,
                'answer' => $correctCount,
                'wrong' => $wrongCount,
                'skipped' => $skippedCount,
                'marks' => $correctCount, // Marks per question
                'negative' => 0,
                'score' => $correctCount, //$score
                'total_time' => $totalTime,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Clear session data
            session()->forget($sessionKey);
            session()->forget('test_start_time_' . $questionPaperId);

            DB::commit();

            return response()->json([
                'success' => true,
                'test_result_id' => $testResultId,
                'redirect_url' => route('student.dashboard', $testResultId)
            ]);
            
            /*return response()->json([
                'success' => true,
                'test_result_id' => $testResultId,
                'redirect_url' => route('student.test-result', $testResultId)
            ]);*/

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Finish test error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error submitting test. Please try again.'
            ], 500);
        }
    }

    public function testResult($testResultId)
    {
        try {
            $user = Auth::guard('student')->user();

            // Get test result
            $testResult = DB::table('test_results')
                        ->where('id', $testResultId)
                        ->where('student_id', $user->student_id)
                        ->first();

            if (!$testResult) {
                Session::flash('message', 'danger#Test result not found.');
                return redirect()->route('student.course-mock-test');
            }

            // Get question paper details
            $questionPaper = DB::table('question_papers')
                            ->where('id', $testResult->question_paper_id)
                            ->first();

            // Get detailed results
            $detailedResults = DB::table('test_all_results')
                              ->join('questions', 'test_all_results.question_id', '=', 'questions.id')
                              ->select('test_all_results.*', 'questions.question', 'questions.answer1',
                                      'questions.answer2', 'questions.answer3', 'questions.answer4',
                                      'questions.question_type')
                              ->where('test_all_results.student_id', $user->student_id)
                              ->where('test_all_results.question_paper_id', $testResult->question_paper_id)
                              ->orderBy('questions.id', 'ASC')
                              ->get();

            // Calculate percentage
            $percentage = $testResult->total_questions > 0
                        ? round(($testResult->answer / $testResult->total_questions) * 100, 2)
                        : 0;

            return view('website.student.test-result', compact('testResult', 'questionPaper', 'detailedResults', 'percentage'));

        } catch (\Exception $e) {
            \Log::error('Test result error: ' . $e->getMessage());
            Session::flash('message', 'danger#Error loading test result. Please try again.');
            return redirect()->route('student.course-mock-test');
        }
    }

    public function getLatestResultId($questionPaperId)
    {
        try {
            $user = Auth::guard('student')->user();

            // Get latest test result for this student and question paper
            $testResult = DB::table('test_results')
                        ->where('student_id', $user->student_id)
                        ->where('question_paper_id', $questionPaperId)
                        ->orderBy('created_at', 'DESC')
                        ->first();

            if ($testResult) {
                return response()->json([
                    'success' => true,
                    'result_id' => $testResult->id,
                    'result' => $testResult
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No result found'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Get latest result error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading result'
            ], 500);
        }
    }
}
