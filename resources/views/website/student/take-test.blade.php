@extends('website.layout')

@section('title', 'Take Test - ' . $questionPaper->question_paper_name)

@push('styles')
<style>
    :root {
        --bg-deep: #0a0e17;
        --bg-panel: #11161f;
        --bg-card: #161c27;
        --bg-card-hover: #1c2330;
        --line: #232b39;
        --line-2: #2a3343;
        --txt-primary: #e6e9ef;
        --txt-secondary: #9aa3b2;
        --txt-muted: #5c6677;
        --accent: #f7931e;
        --accent-2: #ffbb55;
        --green: #22c55e;
        --green-soft: rgba(34, 197, 94, 0.15);
        --blue: #3b82f6;
        --blue-soft: rgba(59, 130, 246, 0.15);
        --amber: #f59e0b;
        --amber-soft: rgba(245, 158, 11, 0.15);
        --rose: #f43f5e;
        --rose-soft: rgba(244, 63, 94, 0.15);
        --violet: #8b5cf6;
        --violet-soft: rgba(139, 92, 246, 0.15);
    }

    body { background: var(--bg-deep); color: var(--txt-primary); }

    /* ===== NAVBAR DARK OVERRIDE ===== */
    .navbar.fixed-top {
        background: rgba(17, 22, 31, 0.92) !important;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #1c2330;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .navbar .navbar-brand, .navbar .navbar-brand:hover { color: #f7931e !important; }
    .navbar-nav .nav-link, .navbar-nav .nav-link:focus { color: #c7ccd6 !important; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #f7931e !important; }
    .navbar .btn-primary-custom {
        background: #f7931e !important; border-color: #f7931e !important; color: #fff !important;
        box-shadow: 0 4px 14px rgba(247, 147, 30, 0.3);
    }
    .navbar .btn-primary-custom:hover { background: #ffbb55 !important; border-color: #ffbb55 !important; }
    .navbar .dropdown-menu { background: #161c27; border: 1px solid #232b39; box-shadow: 0 12px 30px rgba(0,0,0,0.5); }
    .navbar .dropdown-item { color: #c7ccd6; }
    .navbar .dropdown-item:hover, .navbar .dropdown-item:focus { background: #1c2330; color: #f7931e; }
    .navbar .dropdown-item.text-danger { color: #fb7185 !important; }
    .navbar .dropdown-divider { border-top-color: #232b39; }
    .navbar-toggler { border-color: #232b39; padding: 4px 8px; }
    .navbar-toggler-icon { filter: invert(0.9); }

    /* ===== FOOTER DARK OVERRIDE ===== */
    .footer { background: #0a0e17 !important; color: #c7ccd6 !important; border-top: 1px solid #1c2330; }
    .footer h5 { color: #fff; font-weight: 700; }
    .footer p, .footer li, .footer span { color: #9aa3b2; }
    .footer a { color: #9aa3b2; text-decoration: none; }
    .footer a:hover { color: #f7931e; }
    .footer .contact-info li i { color: #f7931e !important; }
    .footer hr { background-color: #1c2330 !important; opacity: 1; }
    .footer .social-links a { background: rgba(247,147,30,0.1); color: #f7931e; border: 1px solid rgba(247,147,30,0.2); }
    .footer .social-links a:hover { background: #f7931e; color: #fff; border-color: #f7931e; }

    /* ===== PAGE SHELL ===== */
    .test-shell { padding: 32px 0 60px; min-height: calc(100vh - 80px); }

    /* ===== PANEL (Bootstrap card override) ===== */
    .card {
        background: var(--bg-card) !important;
        border: 1px solid var(--line) !important;
        border-radius: 18px !important;
        color: var(--txt-primary);
        box-shadow: 0 4px 18px rgba(0,0,0,0.18);
    }
    .card-header {
        background: transparent !important;
        border-bottom: 1px solid var(--line) !important;
        padding: 16px 20px;
        color: var(--txt-primary);
    }
    .card-header h5, .card-header h6 { color: var(--txt-primary); margin: 0; }
    .card-body { padding: 22px; color: var(--txt-primary); }

    /* ===== TEST HEADER ===== */
    .test-header {
        background: linear-gradient(135deg, rgba(247,147,30,0.12) 0%, rgba(247,147,30,0.04) 100%) !important;
        border: 1px solid rgba(247, 147, 30, 0.25) !important;
    }
    .test-header .card-header {
        background: transparent !important;
        border-bottom: none !important;
        color: var(--txt-primary) !important;
        padding: 18px 22px;
    }
    .test-header .card-header h5 { color: var(--txt-primary) !important; }
    .test-header .card-header h5 i { color: var(--accent) !important; }

    .timer-pill {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(244, 63, 94, 0.15);
        color: #fda4af !important;
        font-size: 1.05rem; font-weight: 700;
        padding: 8px 18px;
        border-radius: 999px;
        border: 1px solid rgba(244, 63, 94, 0.35);
        font-family: 'Segoe UI', monospace;
        letter-spacing: 0.5px;
    }
    .timer-pill i { color: #fb7185; }

    /* ===== QUESTION CARD ===== */
    .question-card {
        min-height: 400px;
    }
    .question-card .card-header {
        display: flex; align-items: center;
    }
    .question-card .card-header h6 {
        color: var(--txt-primary);
        font-weight: 600;
    }
    .question-card .card-header h6 #current-question-number {
        color: var(--accent); font-weight: 800;
    }
    .badge.bg-info {
        background: rgba(59, 130, 246, 0.18) !important;
        color: #93c5fd !important;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 999px;
    }

    .question-content h5 {
        color: var(--txt-primary);
        font-weight: 600;
        line-height: 1.5;
        font-size: 1.1rem;
    }

    /* ===== OPTIONS ===== */
    .option-card {
        background: var(--bg-panel) !important;
        border: 2px solid var(--line) !important;
        border-radius: 14px !important;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--txt-primary);
    }
    .option-card:hover {
        background: var(--bg-card-hover) !important;
        border-color: var(--line-2) !important;
        transform: translateX(3px);
    }
    .option-card.selected {
        background: linear-gradient(135deg, rgba(247,147,30,0.18) 0%, rgba(247,147,30,0.08) 100%) !important;
        border-color: var(--accent) !important;
        box-shadow: 0 6px 18px rgba(247, 147, 30, 0.18);
    }
    .option-card label {
        color: var(--txt-primary);
        font-size: 0.95rem;
        cursor: pointer;
        margin: 0;
    }
    .option-card label strong { color: var(--accent); margin-right: 6px; }

    .form-check-input {
        background-color: var(--bg-deep) !important;
        border: 2px solid var(--line-2) !important;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: var(--accent) !important;
        border-color: var(--accent) !important;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.18) !important;
    }

    /* ===== IMAGE QUESTION ===== */
    .question-image-container {
        background: var(--bg-panel) !important;
        padding: 20px;
        border-radius: 14px;
        border: 1px solid var(--line) !important;
    }
    .question-image-container img {
        border: 1px solid var(--line) !important;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .question-image-container img:hover { transform: scale(1.02); cursor: zoom-in; }

    /* ===== NAV BUTTONS ===== */
    .btn-secondary {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line-2) !important;
        color: var(--txt-secondary) !important;
        border-radius: 12px !important;
        padding: 10px 20px !important;
        font-weight: 600;
    }
    .btn-secondary:hover:not(:disabled) {
        background: var(--bg-card-hover) !important;
        color: var(--txt-primary) !important;
        border-color: var(--line-2) !important;
    }
    .btn-secondary:disabled { opacity: 0.4; cursor: not-allowed; }

    .btn-outline-warning {
        background: transparent !important;
        border: 1px solid var(--amber) !important;
        color: var(--amber) !important;
        border-radius: 12px !important;
        padding: 10px 20px !important;
        font-weight: 600;
    }
    .btn-outline-warning:hover {
        background: var(--amber) !important;
        color: #fff !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%) !important;
        border: none !important;
        color: #fff !important;
        border-radius: 12px !important;
        padding: 10px 22px !important;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(247, 147, 30, 0.3);
    }
    .btn-primary:hover { background: linear-gradient(135deg, var(--accent-2) 0%, var(--accent) 100%) !important; transform: translateY(-1px); }

    .btn-success {
        background: linear-gradient(135deg, var(--green) 0%, #15803d 100%) !important;
        border: none !important;
        color: #fff !important;
        border-radius: 12px !important;
        padding: 10px 22px !important;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(34, 197, 94, 0.3);
    }
    .btn-success:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4); }

    /* ===== DESCRIPTIVE ANSWER ===== */
    .descriptive-container {
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 18px;
    }
    .desc-label {
        color: var(--accent-2);
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 10px;
        display: inline-flex;
        align-items: center;
    }
    .desc-label i { color: var(--accent); }
    .descriptive-answer {
        width: 100%;
        background: var(--bg-deep) !important;
        border: 1px solid var(--line) !important;
        color: var(--txt-primary) !important;
        padding: 14px 16px !important;
        border-radius: 12px !important;
        font-size: 0.95rem !important;
        line-height: 1.6;
        resize: vertical;
        min-height: 160px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-family: inherit;
    }
    .descriptive-answer::placeholder { color: var(--txt-muted); }
    .descriptive-answer:focus {
        background: var(--bg-deep) !important;
        border-color: var(--accent) !important;
        color: var(--txt-primary) !important;
        box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.18) !important;
        outline: none;
    }
    .desc-hint {
        margin-top: 10px;
        font-size: 0.82rem;
        color: var(--txt-muted);
    }
    .desc-hint i { color: var(--blue); }

    /* descriptive badge variant */
    .badge.bg-info.descriptive {
        background: rgba(245, 158, 11, 0.18) !important;
        color: #fcd34d !important;
    }

    /* ===== QUESTION PALETTE ===== */
    .question-palette { max-height: 600px; overflow: hidden; }
    .question-palette .card-body { max-height: 540px; overflow-y: auto; }
    .question-palette .card-body::-webkit-scrollbar { width: 6px; }
    .question-palette .card-body::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 999px; }
    .question-palette .card-body::-webkit-scrollbar-track { background: transparent; }

    .question-palette .card-header h6 i { color: var(--accent); }

    .legend-item {
        display: inline-flex; align-items: center;
        margin-right: 16px; margin-bottom: 4px;
    }
    .legend-item small { color: var(--txt-secondary); font-size: 0.78rem; }
    .legend-box {
        width: 18px; height: 18px;
        border: 1px solid var(--line-2);
        margin-right: 8px;
        border-radius: 4px;
    }
    .question-palette hr { border-color: var(--line); opacity: 1; }

    .question-btn {
        width: 42px; height: 42px;
        border: 1px solid var(--line) !important;
        background: var(--bg-panel) !important;
        color: var(--txt-secondary) !important;
        margin: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 10px !important;
        font-weight: 600; font-size: 0.85rem;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .question-btn:hover { transform: scale(1.08); border-color: var(--line-2) !important; color: var(--txt-primary) !important; }
    .question-btn.current {
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%) !important;
        color: #fff !important;
        border-color: var(--accent) !important;
        box-shadow: 0 4px 12px rgba(247, 147, 30, 0.4);
    }
    .question-btn.answered {
        background: linear-gradient(135deg, var(--green) 0%, #15803d 100%) !important;
        color: #fff !important;
        border-color: var(--green) !important;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
    .question-btn.skipped {
        background: linear-gradient(135deg, var(--rose) 0%, #be123c 100%) !important;
        color: #fff !important;
        border-color: var(--rose) !important;
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);
    }

    /* fade-in */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px);} to { opacity: 1; transform: translateY(0);} }
    .fade-in { animation: fadeUp 0.4s ease both; }

    /* helpers */
    .text-muted { color: var(--txt-muted) !important; }

.container-text
{
    width:75% !important;
    margin:0 auto;
}

</style>
@endpush

@section('content')

<div class="test-shell">
    <div class="container" style="width:75% !important;">
        <!-- Test Header -->
        <div class="card test-header mb-4 fade-in">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>{{ $questionPaper->question_paper_name }}
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        @if(!empty($startTimeDisplay) && !empty($endTimeDisplay))
                            <small class="me-2" style="opacity:.75;">{{ $startTimeDisplay }} – {{ $endTimeDisplay }} IST</small>
                        @endif
                        <span class="timer-pill">
                            <i class="far fa-clock"></i>
                            @php
                                $rs = (int) ($remainingSeconds ?? ($remainingMinutes * 60));
                                $hh = floor($rs / 3600);
                                $mm = floor(($rs % 3600) / 60);
                                $ss = $rs % 60;
                                $initialTimer = ($hh > 0)
                                    ? sprintf('%02d:%02d:%02d', $hh, $mm, $ss)
                                    : sprintf('%02d:%02d', $mm, $ss);
                            @endphp
                            <span id="timer">{{ $initialTimer }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Question Display -->
            <div class="col-lg-9">
                <div class="card question-card fade-in">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h6 class="mb-0">Question <span id="current-question-number">1</span> of {{ count($questions) }}</h6>
                            <span class="badge bg-info" id="question-type-badge">Multiple Choice</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Question Content -->
                        <div id="question-container">
                            @foreach($questions as $index => $question)
                            <div class="question-content"
                                 data-question-id="{{ $question->id }}"
                                 data-question-type="{{ $question->question_type }}"
                                 style="display: {{ $index == 0 ? 'block' : 'none' }};">
                                <div class="mb-4">
                                    @if($question->question_type == 1)
                                        <!-- Image Question -->
                                        <div class="question-image-container text-center mb-3">
                                            <img src="{{ config('constants.image_question') . $question->question }}"
                                                 alt="Question {{ $index + 1 }}"
                                                 class="img-fluid"
                                                 style="max-width: 100%; max-height: 500px; object-fit: contain;">
                                        </div>
                                    @else
                                        <!-- Text Question -->
                                        <h5>{{ $question->question }}</h5>
                                    @endif
                                </div>

                                @if($question->question_type == 2)
                                    <!-- Descriptive answer -->
                                    <div class="descriptive-container">
                                        <label class="form-label desc-label">
                                            <i class="fas fa-pen-fancy me-2"></i>Write your answer
                                        </label>
                                        <textarea class="form-control descriptive-answer"
                                                  name="descriptive_{{ $question->id }}"
                                                  rows="6"
                                                  placeholder="Type your answer here..."></textarea>
                                        <div class="desc-hint">
                                            <i class="fas fa-info-circle me-1"></i>
                                            This is a descriptive question. Your answer will be reviewed and graded by the instructor.
                                        </div>
                                    </div>
                                @else
                                    <!-- Options -->
                                    <div class="options-container">
                                        <div class="option-card p-3 mb-3" data-option="1">
                                            <label class="form-check-label w-100">
                                                <input type="radio" name="answer_{{ $question->id }}" value="1" class="form-check-input me-2">
                                                <strong>A.</strong> {{ $question->answer1 }}
                                            </label>
                                        </div>

                                        <div class="option-card p-3 mb-3" data-option="2">
                                            <label class="form-check-label w-100">
                                                <input type="radio" name="answer_{{ $question->id }}" value="2" class="form-check-input me-2">
                                                <strong>B.</strong> {{ $question->answer2 }}
                                            </label>
                                        </div>

                                        <div class="option-card p-3 mb-3" data-option="3">
                                            <label class="form-check-label w-100">
                                                <input type="radio" name="answer_{{ $question->id }}" value="3" class="form-check-input me-2">
                                                <strong>C.</strong> {{ $question->answer3 }}
                                            </label>
                                        </div>

                                        <div class="option-card p-3 mb-3" data-option="4">
                                            <label class="form-check-label w-100">
                                                <input type="radio" name="answer_{{ $question->id }}" value="4" class="form-check-input me-2">
                                                <strong>D.</strong> {{ $question->answer4 }}
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
                            <button type="button" class="btn btn-secondary" id="prev-btn" disabled>
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-outline-warning" id="skip-btn">
                                    Skip
                                </button>
                                <button type="button" class="btn btn-primary" id="next-btn">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button type="button" class="btn btn-success" id="finish-btn" style="display: none;">
                                    <i class="fas fa-check me-2"></i>Finish Test
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Question Palette -->
            <div class="col-lg-3">
                <div class="card question-palette fade-in">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-th me-2"></i>Question Palette</h6>
                    </div>
                    <div class="card-body">
                        <!-- Legend -->
                        <div class="mb-2">
                            <div class="legend-item mb-2">
                                <div class="legend-box" style="background: var(--green); border-color: var(--green);"></div>
                                <small>Answered</small>
                            </div>
                            <div class="legend-item mb-2">
                                <div class="legend-box" style="background: var(--bg-panel);"></div>
                                <small>Not Visited</small>
                            </div>
                            <div class="legend-item mb-2">
                                <div class="legend-box" style="background: var(--accent); border-color: var(--accent);"></div>
                                <small>Current</small>
                            </div>
                            <div class="legend-item mb-2">
                                <div class="legend-box" style="background: var(--rose); border-color: var(--rose);"></div>
                                <small>Skipped</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Question Numbers -->
                        <div class="d-flex flex-wrap">
                            @foreach($questions as $index => $question)
                            <button type="button" class="question-btn {{ $index == 0 ? 'current' : '' }}"
                                    data-index="{{ $index }}"
                                    data-question-id="{{ $question->id }}">
                                {{ $index + 1 }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card mt-3 fade-in">
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-success w-100" id="submit-test-btn">
                            <i class="fas fa-check-circle me-2"></i>Submit Test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Data -->
<input type="hidden" id="question-paper-id" value="{{ $questionPaper->id }}">
<input type="hidden" id="total-questions" value="{{ count($questions) }}">
<input type="hidden" id="duration" value="{{ $questionPaper->duration ?? 60 }}">
<input type="hidden" id="remaining-minutes" value="{{ $remainingMinutes }}">
<input type="hidden" id="remaining-seconds" value="{{ $remainingSeconds ?? ($remainingMinutes * 60) }}">

@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    const questionPaperId = document.getElementById('question-paper-id').value;
    const totalQuestions = parseInt(document.getElementById('total-questions').value);
    const duration = parseInt(document.getElementById('duration').value);
    const remainingMinutes = parseInt(document.getElementById('remaining-minutes').value);
    const remainingSecondsInit = parseInt(document.getElementById('remaining-seconds').value);

    let currentQuestionIndex = 0;
    let answers = {};
    let timerInterval;
    let testCompleted = false;

    document.addEventListener('DOMContentLoaded', function() {
        initializeTimer();
        initializeEventListeners();
        updateNavigationButtons();
        initializeBackGuard();
    });

    function initializeBackGuard() {
        // Push a sentinel state so the FIRST back press triggers popstate
        // instead of leaving the page directly.
        history.pushState({ takeTestGuard: true }, '', window.location.href);

        window.addEventListener('popstate', function () {
            if (testCompleted) {
                // Test already finished/submitted — let navigation proceed.
                return;
            }

            // Re-push immediately so the user stays on the test page until
            // they confirm in the SweetAlert dialog.
            history.pushState({ takeTestGuard: true }, '', window.location.href);

            Swal.fire({
                title: 'Are you sure, you want to leave?',
                text: 'Your current progress will be submitted and saved as your test result.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, leave test',
                cancelButtonText: 'No, stay',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#f7931e',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(function (result) {
                if (result.isConfirmed) {
                    submitAndGoToDashboard();
                }
            });
        });
    }

    // Save whatever answer the student currently has selected, POST the
    // partial test to /finish-test (which writes the row to test_results +
    // test_all_results), then send them to the dashboard. Marks the test
    // completed up front so the back-guard doesn't prompt again.
    function submitAndGoToDashboard() {
        testCompleted = true;
        try { clearInterval(timerInterval); } catch (e) {}
        try { saveCurrentAnswer(); } catch (e) {}

        Swal.fire({
            title: 'Saving your test...',
            text: 'Please wait while we record your answers.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function () { Swal.showLoading(); }
        });

        const dashboardUrl = '{{ route('student.dashboard') }}';

        fetch('{{ route("student.finish-test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ question_paper_id: questionPaperId })
        })
        .then(function (r) { return r.json().catch(function () { return {}; }); })
        .then(function () {
            window.location.href = dashboardUrl;
        })
        .catch(function () {
            // Even if the save call errored, still send the student to the
            // dashboard — they explicitly chose to leave.
            window.location.href = dashboardUrl;
        });
    }
    

    function initializeTimer() {
        let timeLeft = isNaN(remainingSecondsInit) ? (remainingMinutes * 60) : remainingSecondsInit;
        const timerDisplay = document.getElementById('timer');

        function formatTime(t) {
            const h = Math.floor(t / 3600);
            const m = Math.floor((t % 3600) / 60);
            const s = t % 60;
            const pad = n => n.toString().padStart(2, '0');
            return h > 0
                ? `${pad(h)}:${pad(m)}:${pad(s)}`
                : `${pad(m)}:${pad(s)}`;
        }

        timerDisplay.textContent = formatTime(timeLeft);

        if (timeLeft <= 0) {
            autoSubmitTest();
            return;
        }

        timerInterval = setInterval(function() {
            timeLeft--;
            timerDisplay.textContent = formatTime(Math.max(0, timeLeft));

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                autoSubmitTest();
            }
        }, 1000);
    }

    function initializeEventListeners() {
        document.querySelectorAll('.option-card').forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                this.closest('.options-container').querySelectorAll('.option-card').forEach(c => {
                    c.classList.remove('selected');
                });
                this.classList.add('selected');
                saveCurrentAnswer();
            });
        });

        // Descriptive textareas — save on input (debounced) and on blur
        document.querySelectorAll('.descriptive-answer').forEach(ta => {
            let debounceTimer;
            ta.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(saveCurrentAnswer, 600);
            });
            ta.addEventListener('blur', saveCurrentAnswer);
        });

        // Set initial badge for the first question
        updateQuestionBadge(0);

        document.getElementById('prev-btn').addEventListener('click', previousQuestion);
        document.getElementById('next-btn').addEventListener('click', nextQuestion);
        document.getElementById('skip-btn').addEventListener('click', skipQuestion);
        document.getElementById('finish-btn').addEventListener('click', confirmFinishTest);
        document.getElementById('submit-test-btn').addEventListener('click', confirmFinishTest);

        document.querySelectorAll('.question-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                goToQuestion(index);
            });
        });
    }

    function saveCurrentAnswer() {
        const currentQuestion = document.querySelectorAll('.question-content')[currentQuestionIndex];
        const questionId = currentQuestion.getAttribute('data-question-id');
        const qType = currentQuestion.getAttribute('data-question-type');
        const paletteBtn = document.querySelector(`.question-btn[data-question-id="${questionId}"]`);

        if (qType === '2') {
            // Descriptive — read textarea
            const ta = currentQuestion.querySelector('.descriptive-answer');
            const value = ta ? ta.value.trim() : '';
            if (value !== '') {
                answers[questionId] = value;
                paletteBtn.classList.remove('skipped');
                paletteBtn.classList.add('answered');
                submitAnswerToServer(questionId, value);
            } else {
                // Empty — remove any prior answer
                if (answers[questionId] !== undefined) {
                    delete answers[questionId];
                    submitAnswerToServer(questionId, '');
                }
                paletteBtn.classList.remove('answered');
            }
            return;
        }

        // Objective / image — read radio
        const selectedOption = currentQuestion.querySelector('input[type="radio"]:checked');
        if (selectedOption) {
            answers[questionId] = selectedOption.value;
            paletteBtn.classList.remove('skipped');
            paletteBtn.classList.add('answered');
            submitAnswerToServer(questionId, selectedOption.value);
        }
    }

    function updateQuestionBadge(index) {
        const currentQuestion = document.querySelectorAll('.question-content')[index];
        const qType = currentQuestion.getAttribute('data-question-type');
        const badge = document.getElementById('question-type-badge');
        if (!badge) return;
        if (qType === '2') {
            badge.textContent = 'Descriptive';
            badge.classList.add('descriptive');
        } else if (qType === '1') {
            badge.textContent = 'Image Question';
            badge.classList.remove('descriptive');
        } else {
            badge.textContent = 'Multiple Choice';
            badge.classList.remove('descriptive');
        }
    }

    function submitAnswerToServer(questionId, answer) {
        fetch('{{ route("student.submit-answer") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ question_paper_id: questionPaperId, question_id: questionId, answer: answer })
        })
        .then(response => response.json())
        .then(data => console.log('Answer saved:', data))
        .catch(error => console.error('Error saving answer:', error));
    }

    function previousQuestion() {
        if (currentQuestionIndex > 0) {
            saveCurrentAnswer();
            goToQuestion(currentQuestionIndex - 1);
        }
    }

    function nextQuestion() {
        if (currentQuestionIndex < totalQuestions - 1) {
            saveCurrentAnswer();
            goToQuestion(currentQuestionIndex + 1);
        }
    }

    function skipQuestion() {
        const currentQuestion = document.querySelectorAll('.question-content')[currentQuestionIndex];
        const questionId = currentQuestion.getAttribute('data-question-id');
        const paletteBtn = document.querySelector(`.question-btn[data-question-id="${questionId}"]`);
        if (!paletteBtn.classList.contains('answered')) {
            paletteBtn.classList.add('skipped');
        }
        if (currentQuestionIndex < totalQuestions - 1) {
            goToQuestion(currentQuestionIndex + 1);
        }
    }

    function goToQuestion(index) {
        document.querySelectorAll('.question-content').forEach(q => q.style.display = 'none');
        const targetQuestion = document.querySelectorAll('.question-content')[index];
        targetQuestion.style.display = 'block';
        currentQuestionIndex = index;
        document.getElementById('current-question-number').textContent = index + 1;
        document.querySelectorAll('.question-btn').forEach(btn => btn.classList.remove('current'));
        document.querySelectorAll('.question-btn')[index].classList.add('current');
        loadSavedAnswer(index);
        updateQuestionBadge(index);
        updateNavigationButtons();
    }

    function loadSavedAnswer(index) {
        const currentQuestion = document.querySelectorAll('.question-content')[index];
        const questionId = currentQuestion.getAttribute('data-question-id');
        const qType = currentQuestion.getAttribute('data-question-type');
        const saved = answers[questionId];
        if (saved === undefined) return;

        if (qType === '2') {
            const ta = currentQuestion.querySelector('.descriptive-answer');
            if (ta) ta.value = saved;
        } else {
            const radio = currentQuestion.querySelector(`input[value="${saved}"]`);
            if (radio) {
                radio.checked = true;
                radio.closest('.option-card').classList.add('selected');
            }
        }
    }

    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const finishBtn = document.getElementById('finish-btn');
        prevBtn.disabled = currentQuestionIndex === 0;
        if (currentQuestionIndex === totalQuestions - 1) {
            nextBtn.style.display = 'none';
            finishBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'inline-block';
            finishBtn.style.display = 'none';
        }
    }

    function confirmFinishTest() {
        Swal.fire({
            title: 'Submit Test?',
            text: 'Are you sure you want to submit the test? You cannot change your answers after submission.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Submit!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) finishTest();
        });
    }

    function finishTest() {
        saveCurrentAnswer();
        clearInterval(timerInterval);
        testCompleted = true;

        Swal.fire({
            title: 'Submitting Test...',
            text: 'Please wait while we process your answers.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('{{ route("student.finish-test") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ question_paper_id: questionPaperId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Your test has been submitted successfully.',
                    icon: 'success',
                    confirmButtonColor: '#22c55e',
                    allowOutsideClick: false
                }).then(() => window.location.href = data.redirect_url);
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Error submitting test. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#f43f5e'
                });
                testCompleted = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Error submitting test. Please try again.',
                icon: 'error',
                confirmButtonColor: '#f43f5e'
            });
            testCompleted = false;
        });
    }

    function autoSubmitTest() {
        Swal.fire({
            title: 'Your time is over',
            text: 'Click OK to save test and view results. Not attempted questions will be marked as skipped.',
            icon: 'warning',
            confirmButtonColor: '#f43f5e',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => finishTest());
    }

    window.addEventListener('beforeunload', function(e) {
        if (!testCompleted) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

})();
</script>
@endpush
