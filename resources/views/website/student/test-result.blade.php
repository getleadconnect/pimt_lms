@extends('website.layout')

@section('title', 'Test Result - ' . $questionPaper->question_paper_name)

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
    .result-shell { padding: 32px 0 60px; min-height: calc(100vh - 80px); }

    /* breadcrumbs */
    .breadcrumb { background: transparent; padding: 0; margin: 0 0 14px 0; }
    .breadcrumb-item a { color: var(--txt-secondary); text-decoration: none; }
    .breadcrumb-item a:hover { color: var(--accent); }
    .breadcrumb-item.active { color: var(--txt-primary); }
    .breadcrumb-item + .breadcrumb-item::before { color: var(--txt-muted); }

    /* page header */
    .page-header { display:flex; align-items:center; gap:14px; margin-bottom:24px; }
    .page-header-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%);
        color: #fff;
        display:flex; align-items:center; justify-content:center;
        font-size: 1.2rem;
        box-shadow: 0 6px 18px rgba(247, 147, 30, 0.35);
        flex-shrink: 0;
    }
    .page-title { font-size: 1.4rem; font-weight: 700; color: var(--txt-primary); margin: 0; letter-spacing: -0.3px; }
    .page-sub { font-size: 0.82rem; color: var(--txt-muted); margin: 2px 0 0; }

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
    .card-header h5 i, .card-header h6 i { color: var(--accent) !important; }
    .card-body { padding: 22px; color: var(--txt-primary); }

    /* ===== SUMMARY STAT CARDS ===== */
    .info-stat-card {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        border-radius: 14px !important;
        padding: 18px !important;
        margin-bottom: 0 !important;
        display: flex; align-items: center; gap: 14px;
        transition: all 0.25s ease;
        text-decoration: none;
        color: inherit;
    }
    .info-stat-card:hover {
        background: var(--bg-card-hover) !important;
        border-color: var(--line-2) !important;
        transform: translateY(-3px);
    }
    .info-stat-icon {
        width: 44px; height: 44px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px;
    }
    .info-stat-icon i { font-size: 1.2rem; }
    .info-stat-content { flex: 1; display: flex; flex-direction: column; min-width: 0; }
    .info-stat-value { font-size: 1.5rem; font-weight: 700; margin: 0; color: var(--txt-primary); line-height: 1; }
    .info-stat-label { font-size: 0.78rem; color: var(--txt-secondary); margin-top: 6px; }

    .info-stat-card.card-score      .info-stat-icon { background: rgba(247,147,30,0.15); color: var(--accent); }
    .info-stat-card.card-percentage .info-stat-icon { background: var(--blue-soft);     color: var(--blue); }
    .info-stat-card.card-correct    .info-stat-icon { background: var(--green-soft);    color: var(--green); }
    .info-stat-card.card-wrong      .info-stat-icon { background: var(--rose-soft);     color: var(--rose); }
    .info-stat-card.card-skipped    .info-stat-icon { background: var(--amber-soft);    color: var(--amber); }
    .info-stat-card.card-total      .info-stat-icon { background: var(--violet-soft);   color: var(--violet); }
    .info-stat-card.card-time       .info-stat-icon { background: rgba(148,163,184,0.15); color: #94a3b8; }
    .info-stat-card.card-date       .info-stat-icon { background: rgba(148,163,184,0.15); color: #94a3b8; }

    /* ===== PERFORMANCE BREAKDOWN ===== */
    .perf-panel {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        border-radius: 14px !important;
        padding: 18px !important;
    }
    .perf-panel h6 { color: var(--txt-secondary); font-weight: 600; }
    .progress {
        background: var(--bg-deep) !important;
        border: 1px solid var(--line);
        border-radius: 999px !important;
        overflow: hidden;
    }
    .progress-bar { font-weight: 600; font-size: 0.78rem; }
    .progress-bar.bg-success { background: linear-gradient(135deg, var(--green) 0%, #15803d 100%) !important; }
    .progress-bar.bg-danger  { background: linear-gradient(135deg, var(--rose)  0%, #be123c 100%) !important; }
    .progress-bar.bg-warning { background: linear-gradient(135deg, var(--amber) 0%, #b45309 100%) !important; }

    /* ===== DETAILED RESULT BLOCKS ===== */
    .result-block {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        border-left: 4px solid var(--line-2) !important;
        border-radius: 14px !important;
        padding: 18px !important;
        margin-bottom: 16px;
    }
    .result-block.correct-answer { border-left-color: var(--green) !important; background: rgba(34, 197, 94, 0.04) !important; }
    .result-block.wrong-answer   { border-left-color: var(--rose)  !important; background: rgba(244, 63, 94, 0.04) !important; }
    .result-block.skipped-answer { border-left-color: var(--amber) !important; background: rgba(245, 158, 11, 0.04) !important; }

    .result-block h6 { color: var(--txt-primary); font-weight: 700; margin: 0; }
    .result-block p strong { color: var(--txt-primary); }
    .result-block > p { color: var(--txt-primary); font-size: 1rem; line-height: 1.5; }

    .badge.bg-success { background: var(--green-soft) !important; color: #4ade80 !important; padding: 5px 12px; border-radius: 999px; font-weight: 600; }
    .badge.bg-danger  { background: var(--rose-soft)  !important; color: #fb7185 !important; padding: 5px 12px; border-radius: 999px; font-weight: 600; }
    .badge.bg-warning { background: var(--amber-soft) !important; color: #fcd34d !important; padding: 5px 12px; border-radius: 999px; font-weight: 600; }

    /* ===== ANSWER OPTIONS ===== */
    .answer-option {
        background: var(--bg-card) !important;
        border: 1px solid var(--line) !important;
        border-radius: 10px;
        padding: 12px 14px;
        margin: 6px 0;
        color: var(--txt-primary);
        font-size: 0.92rem;
    }
    .answer-option strong { color: var(--accent); margin-right: 6px; }
    .answer-option.correct {
        background: rgba(34, 197, 94, 0.1) !important;
        border-color: rgba(34, 197, 94, 0.4) !important;
    }
    .answer-option.wrong.user-selected {
        background: rgba(244, 63, 94, 0.1) !important;
        border-color: rgba(244, 63, 94, 0.4) !important;
    }
    .answer-option.user-selected { font-weight: 600; }
    .answer-option .text-success { color: var(--green) !important; }
    .answer-option .text-danger  { color: var(--rose) !important; }

    /* ===== IMAGE QUESTION ===== */
    .question-image-container {
        background: var(--bg-card) !important;
        padding: 18px;
        border-radius: 14px;
        border: 1px solid var(--line) !important;
    }
    .question-image-container img {
        border: 1px solid var(--line) !important;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .question-image-container img:hover { transform: scale(1.02); cursor: zoom-in; }

    /* ===== ACTION BUTTONS ===== */
    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%) !important;
        border: none !important;
        color: #fff !important;
        border-radius: 12px !important;
        padding: 10px 22px !important;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(247, 147, 30, 0.3);
        margin: 4px;
    }
    .btn-primary:hover { background: linear-gradient(135deg, var(--accent-2) 0%, var(--accent) 100%) !important; transform: translateY(-1px); }

    .btn-info {
        background: linear-gradient(135deg, var(--blue) 0%, #1d4ed8 100%) !important;
        border: none !important;
        color: #fff !important;
        border-radius: 12px !important;
        padding: 10px 22px !important;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
        margin: 4px;
    }
    .btn-info:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4); color: #fff !important; }

    .btn-secondary {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line-2) !important;
        color: var(--txt-secondary) !important;
        border-radius: 12px !important;
        padding: 10px 22px !important;
        font-weight: 600;
        margin: 4px;
    }
    .btn-secondary:hover { background: var(--bg-card-hover) !important; color: var(--txt-primary) !important; }

    /* text utilities */
    .text-primary { color: var(--accent) !important; }
    .text-muted   { color: var(--txt-muted) !important; }
    .text-success { color: var(--green) !important; }
    .text-danger  { color: var(--rose) !important; }
    .text-warning { color: var(--amber) !important; }

    /* fade-in */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px);} to { opacity: 1; transform: translateY(0);} }
    .fade-in { animation: fadeUp 0.4s ease both; }

    /* print */
    @media print {
        body { background: white !important; color: black !important; }
        .navbar, .footer, .btn { display: none !important; }
        .card, .result-block, .info-stat-card, .perf-panel { background: white !important; border-color: #ddd !important; color: black !important; }
    }
</style>
@endpush

@section('content')

<div class="result-shell">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.course-mock-test') }}">Mock Tests</a></li>
                <li class="breadcrumb-item active">Test Result</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header fade-in">
            <div class="page-header-icon"><i class="fas fa-chart-bar"></i></div>
            <div>
                <h1 class="page-title">Test Result</h1>
                <p class="page-sub">{{ $questionPaper->question_paper_name }}</p>
            </div>
        </div>

        <!-- Result Summary -->
        <div class="card mb-4 fade-in">
            <div class="card-header">
                <h5 class="fw-semibold">
                    <i class="fas fa-trophy me-2"></i>Test Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-score">
                            <div class="info-stat-icon"><i class="fas fa-star"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value">{{ $testResult->score }}</div>
                                <div class="info-stat-label">Score</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-percentage">
                            <div class="info-stat-icon"><i class="fas fa-percent"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value">{{ $percentage }}%</div>
                                <div class="info-stat-label">Percentage</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-correct">
                            <div class="info-stat-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value">{{ $testResult->answer }}</div>
                                <div class="info-stat-label">Correct</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-wrong">
                            <div class="info-stat-icon"><i class="fas fa-times-circle"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value">{{ $testResult->wrong }}</div>
                                <div class="info-stat-label">Wrong</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-skipped">
                            <div class="info-stat-icon"><i class="fas fa-minus-circle"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value">{{ $testResult->skipped }}</div>
                                <div class="info-stat-label">Skipped</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-total">
                            <div class="info-stat-icon"><i class="fas fa-list"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value">{{ $testResult->total_questions }}</div>
                                <div class="info-stat-label">Total Questions</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-time">
                            <div class="info-stat-icon"><i class="far fa-clock"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value">{{ $testResult->total_time }}</div>
                                <div class="info-stat-label">Minutes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-stat-card card-date">
                            <div class="info-stat-icon"><i class="fas fa-calendar"></i></div>
                            <div class="info-stat-content">
                                <div class="info-stat-value" style="font-size: 1rem;">{{ \Carbon\Carbon::parse($testResult->test_date)->format('d M Y') }}</div>
                                <div class="info-stat-label">Test Date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="perf-panel">
                            <h6 class="text-center mb-3">Performance Breakdown</h6>
                            <div class="progress" style="height: 28px;">
                                @php
                                    $totalQ = $testResult->total_questions ?: 1;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ ($testResult->answer / $totalQ) * 100 }}%">
                                    {{ $testResult->answer }} Correct
                                </div>
                                <div class="progress-bar bg-danger" role="progressbar"
                                     style="width: {{ ($testResult->wrong / $totalQ) * 100 }}%">
                                    {{ $testResult->wrong }} Wrong
                                </div>
                                <div class="progress-bar bg-warning" role="progressbar"
                                     style="width: {{ ($testResult->skipped / $totalQ) * 100 }}%">
                                    {{ $testResult->skipped }} Skipped
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Results -->
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="fw-semibold">
                    <i class="fas fa-list-alt me-2"></i>Detailed Question-wise Result
                </h5>
            </div>
            <div class="card-body">
                @foreach($detailedResults as $index => $result)
                <div class="result-block
                    @if($result->skipped_status == 1)
                        skipped-answer
                    @elseif($result->answer == $result->correct_answer)
                        correct-answer
                    @else
                        wrong-answer
                    @endif">
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <h6 class="fw-bold">Question {{ $index + 1 }}</h6>
                        @if($result->skipped_status == 1)
                            <span class="badge bg-warning"><i class="fas fa-minus-circle me-1"></i>Skipped</span>
                        @elseif($result->answer == $result->correct_answer)
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Correct</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Wrong</span>
                        @endif
                    </div>

                    @if($result->question_type == 1)
                        <div class="question-image-container text-center mb-3">
                            <img src="{{ config('constants.image_question') . $result->question }}"
                                 alt="Question {{ $index + 1 }}"
                                 class="img-fluid"
                                 style="max-width: 100%; max-height: 400px; object-fit: contain;">
                        </div>
                    @else
                        <p class="mb-3"><strong>{{ $result->question }}</strong></p>
                    @endif

                    <div class="options">
                        @for($i = 1; $i <= 4; $i++)
                            @php
                                $answerKey = 'answer' . $i;
                                $isCorrect = ($result->correct_answer == $i);
                                $isUserAnswer = ($result->answer == $i);
                            @endphp
                            <div class="answer-option {{ $isCorrect ? 'correct' : '' }} {{ $isUserAnswer ? 'user-selected wrong' : '' }}">
                                <strong>{{ chr(64 + $i) }}.</strong> {{ $result->$answerKey }}
                                @if($isCorrect)
                                    <i class="fas fa-check-circle text-success float-end"></i>
                                @endif
                                @if($isUserAnswer && !$isCorrect)
                                    <i class="fas fa-times-circle text-danger float-end"></i>
                                    <small class="text-danger float-end me-2">(Your Answer)</small>
                                @endif
                                @if($isUserAnswer && $isCorrect)
                                    <small class="text-success float-end me-2">(Your Answer)</small>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <a href="{{ route('student.course-mock-test') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Mock Tests
                </a>
                <a href="{{ route('student.my-results') }}" class="btn btn-info">
                    <i class="fas fa-chart-line me-2"></i>View All Results
                </a>
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print me-2"></i>Print Result
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Test result page loaded');
    });
</script>
@endpush
