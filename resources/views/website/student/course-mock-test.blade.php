@extends('website.layout')

@section('title', 'Mock Test')

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
    .navbar .navbar-brand,
    .navbar .navbar-brand:hover { color: #f7931e !important; }
    .navbar-nav .nav-link,
    .navbar-nav .nav-link:focus { color: #c7ccd6 !important; }
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active { color: #f7931e !important; }
    .navbar .btn-primary-custom {
        background: #f7931e !important; border-color: #f7931e !important; color: #fff !important;
        box-shadow: 0 4px 14px rgba(247, 147, 30, 0.3);
    }
    .navbar .btn-primary-custom:hover { background: #ffbb55 !important; border-color: #ffbb55 !important; }
    .navbar .dropdown-menu { background: #161c27; border: 1px solid #232b39; box-shadow: 0 12px 30px rgba(0,0,0,0.5); }
    .navbar .dropdown-item { color: #c7ccd6; }
    .navbar .dropdown-item:hover, .navbar .dropdown-item:focus { background: #1c2330; color: #f7931e; }
    .navbar .dropdown-item.text-danger { color: #fb7185 !important; }
    .navbar .dropdown-item.text-danger:hover { background: rgba(244,63,94,0.1); color: #fda4af !important; }
    .navbar .dropdown-divider { border-top-color: #232b39; }
    .navbar-toggler { border-color: #232b39; padding: 4px 8px; }
    .navbar-toggler-icon { filter: invert(0.9); }

    /* ===== FOOTER DARK OVERRIDE ===== */
    .footer { background: #0a0e17 !important; color: #c7ccd6 !important; border-top: 1px solid #1c2330; }
    .footer h5 { color: #fff; font-weight: 700; }
    .footer p, .footer li, .footer span { color: #9aa3b2; }
    .footer a { color: #9aa3b2; text-decoration: none; transition: color 0.2s ease; }
    .footer a:hover { color: #f7931e; }
    .footer .contact-info li i { color: #f7931e !important; }
    .footer hr { background-color: #1c2330 !important; opacity: 1; }
    .footer .social-links a {
        background: rgba(247,147,30,0.1); color: #f7931e;
        border: 1px solid rgba(247,147,30,0.2);
    }
    .footer .social-links a:hover { background: #f7931e; color: #fff; border-color: #f7931e; }

    /* ===== PAGE SHELL ===== */
    .mock-shell {
        padding: 32px 0 60px;
        min-height: calc(100vh - 80px);
    }

    /* breadcrumbs */
    .breadcrumb {
        background: transparent;
        padding: 0; margin: 0 0 18px 0;
    }
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
    }
    .page-title { font-size: 1.6rem; font-weight: 700; color: var(--txt-primary); margin: 0; letter-spacing: -0.4px; }
    .page-sub { font-size: 0.85rem; color: var(--txt-muted); margin: 2px 0 0; }

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
    }
    .card-header h5, .card-header h6 { color: var(--txt-primary); margin: 0; }
    .card-header i.text-primary { color: var(--accent) !important; }
    .card-body { padding: 22px; }

    /* form labels & selects */
    .form-label { color: var(--txt-secondary); font-size: 0.85rem; margin-bottom: 8px; }
    .form-label i.text-primary { color: var(--accent) !important; }

    .form-select, .form-control {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        color: var(--txt-primary) !important;
        border-radius: 12px !important;
        padding: 10px 14px !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-select:focus, .form-control:focus {
        background: var(--bg-panel) !important;
        border-color: var(--accent) !important;
        color: var(--txt-primary) !important;
        box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.15) !important;
        outline: none;
    }
    .form-select-lg { font-size: 0.95rem !important; }
    .form-select option { background: var(--bg-panel); color: var(--txt-primary); }
    .form-select:disabled { background: var(--bg-deep) !important; color: var(--txt-muted) !important; cursor: not-allowed; }

    /* ===== STAT MINI-CARDS (Total Q's, Duration, Marks) ===== */
    .stat-mini {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        border-radius: 14px !important;
        padding: 16px !important;
        display: flex; align-items: center; justify-content: space-between;
        gap: 14px;
    }
    .stat-mini-left { display:flex; align-items:center; gap:12px; }
    .stat-mini-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display:flex; align-items:center; justify-content:center;
        font-size: 1.05rem;
    }
    .stat-mini-icon.green { background: var(--green-soft); color: var(--green); }
    .stat-mini-icon.amber { background: var(--amber-soft); color: var(--amber); }
    .stat-mini-icon.blue  { background: var(--blue-soft);  color: var(--blue); }
    .stat-mini-icon.violet{ background: var(--violet-soft);color: var(--violet); }
    .stat-mini-icon.rose  { background: var(--rose-soft);  color: var(--rose); }
    .stat-mini-label { font-size: 0.78rem; color: var(--txt-secondary); }
    .stat-mini-value { font-size: 1.2rem; font-weight: 700; color: var(--txt-primary); margin: 0; line-height: 1; }

    /* ===== ALERTS ===== */
    .alert { border: 1px solid var(--line); border-radius: 14px; color: var(--txt-primary); }
    .alert-info {
        background: rgba(59, 130, 246, 0.08) !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
        color: #93c5fd !important;
    }
    .alert-warning {
        background: rgba(245, 158, 11, 0.08) !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
        color: #fcd34d !important;
    }
    .alert-warning .alert-heading { color: var(--amber); }
    .alert-warning ul { color: var(--txt-secondary); margin-bottom: 0; }
    .alert-warning ul li { color: var(--txt-secondary); }

    /* ===== BUTTONS ===== */
    .btn-success {
        background: linear-gradient(135deg, var(--green) 0%, #15803d 100%) !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 12px 24px !important;
        font-weight: 600;
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.25);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(34, 197, 94, 0.35); }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%) !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 10px 20px !important;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(247, 147, 30, 0.3);
    }
    .btn-primary:hover { background: linear-gradient(135deg, var(--accent-2) 0%, var(--accent) 100%) !important; transform: translateY(-2px); }

    /* ===== TEST LIST ITEMS ===== */
    .list-group-flush, .list-group { background: transparent !important; }
    .test-item {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        border-radius: 14px !important;
        padding: 14px !important;
        margin: 0 0 10px 0 !important;
        text-decoration: none !important;
        color: var(--txt-primary) !important;
        transition: all 0.2s ease;
        display: block;
    }
    .test-item:hover {
        background: var(--bg-card-hover) !important;
        border-color: var(--line-2) !important;
        transform: translateX(2px);
    }
    .test-item.active {
        background: linear-gradient(135deg, rgba(247,147,30,0.15) 0%, rgba(247,147,30,0.05) 100%) !important;
        border-color: rgba(247, 147, 30, 0.4) !important;
    }
    .test-item h6 {
        color: var(--txt-primary) !important;
        font-size: 0.95rem; font-weight: 600;
        margin: 0;
    }
    .test-item h6 i { color: var(--accent) !important; }
    .test-item .text-muted { color: var(--txt-secondary) !important; }
    .test-item .text-muted i { color: var(--accent); }
    .test-item .badge { background: rgba(59, 130, 246, 0.18) !important; color: #93c5fd !important; font-weight: 600; padding: 4px 10px; border-radius: 999px; }

    /* test list scroll */
    #test_list { padding: 6px; max-height: 600px; overflow-y: auto; }
    #test_list::-webkit-scrollbar { width: 6px; }
    #test_list::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 999px; }
    #test_list::-webkit-scrollbar-track { background: transparent; }

    /* empty / loading states inside list */
    .empty-block { text-align: center; padding: 40px 16px; color: var(--txt-muted); }
    .empty-block i { color: var(--line-2); }
    .empty-block p { color: var(--txt-secondary); margin: 12px 0 0; }
    .spinner-border { color: var(--accent) !important; }

    /* result mini-cards */
    .result-tile {
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 18px 12px;
        text-align: center;
    }
    .result-tile i { font-size: 1.5rem; margin-bottom: 8px; display:block; }
    .result-tile h5 { color: var(--txt-primary); font-weight: 700; margin: 4px 0 2px; font-size: 1.3rem; }
    .result-tile small { color: var(--txt-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .result-tile.score i { color: var(--accent); }
    .result-tile.pct   i { color: var(--blue); }
    .result-tile.correct i { color: var(--green); }
    .result-tile.wrong  i { color: var(--rose); }
    .result-tile.skip   i { color: var(--amber); }

    .text-primary { color: var(--accent) !important; }
    .text-info    { color: var(--blue) !important; }
    .text-success { color: var(--green) !important; }
    .text-warning { color: var(--amber) !important; }
    .text-danger  { color: var(--rose) !important; }
    .text-muted   { color: var(--txt-muted) !important; }

    /* fade-in */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px);} to { opacity: 1; transform: translateY(0);} }
    .fade-in { animation: fadeUp 0.4s ease both; }

.container-text
{
    width:75% !important;
    margin:0 auto;
}

</style>
@endpush

@section('content')

<div class="mock-shell">
    <div class="container" style="width:75% !important;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Examinations</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header fade-in">
            <div class="page-header-icon"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <h1 class="page-title" id="page_title">Examinations</h1>
                <p class="page-sub">Choose a course and exam section to start practicing.</p>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row g-4">
            <!-- Left Column - Test Details -->
            <div class="col-lg-8">
                <!-- No Test Selected Message -->
                <div class="alert alert-info d-flex align-items-center fade-in" id="no_test_selected" role="alert"
                     @if($preselectCourseId && count($qPapers) > 0) style="display:none;" @endif>
                   <i class="fas fa-graduation-cap text-primary me-2"></i>
                    <div>Course: <strong>{{$myCourses->course_name}}</strong></div>
                </div>

                <!-- Test Section -->
                <div class="card fade-in" id="test_section" style="display: none;">
                    <div class="card-header">
                        <h5 class="fw-semibold">
                            <i class="fas fa-clipboard-list me-2 text-primary"></i>
                            <span id="current_test_title">Mock Test</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Test Info Section -->
                        <div id="test_info_section">
                            <!-- Test Stats -->

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="stat-mini">
                                        <div class="stat-mini-left">
                                            <div class="stat-mini-icon blue"><i class="fas fa-question-circle"></i></div>
                                            <div class="stat-mini-label">Total Questions</div>
                                        </div>
                                        <h4 class="stat-mini-value" id="total_questions">0</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-mini">
                                        <div class="stat-mini-left">
                                            <div class="stat-mini-icon amber"><i class="far fa-clock"></i></div>
                                            <div class="stat-mini-label">Duration</div>
                                        </div>
                                        <h4 class="stat-mini-value" id="test_duration">60 mins</h4>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="stat-mini">
                                        <div class="stat-mini-left">
                                            <div class="stat-mini-icon green"><i class="fas fa-clock"></i></div>
                                            <div class="stat-mini-label">Time</div>
                                        </div>
                                        <h4 class="stat-mini-value" id="total_marks" >0</h4>
                                    </div>
                                </div>

                                <!--<div class="col-md-4">
                                    <div class="stat-mini">
                                        <div class="stat-mini-left">
                                            <div class="stat-mini-icon green"><i class="fas fa-star"></i></div>
                                            <div class="stat-mini-label">Total Marks</div>
                                        </div>
                                        <h4 class="stat-mini-value" id="total_marks">0</h4>
                                    </div>
                                </div> -->

                            </div>

                    <div class="alert alert-info d-flex align-items-center fade-in" id="no_test_selected" role="alert" style="padding:3px 10px;">
                        <div class="row" style="width:100%;">
                            <div class="col-6"><i class="fas fa-calendar text-primary me-2"></i><span id="start__date"></span></div>
                            <div class="col-6" style="text-align:right;"><i class="fas fa-clock text-primary me-2"></i><span id="start__time"></span></div>
                        </div>
                    </div>

                            <!-- Instructions -->
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Instructions
                                </h6>
                                <ul class="mb-0">
                                    <li>This test includes objective-type and descriptive questions.</li>
                                    <li>Read each question carefully before answering</li>
                                    <li>You can navigate between questions using the question palette</li>
                                    <li>Click "Submit Test" when you're done</li>
                                    <li>Your progress will be saved automatically</li>
                                </ul>
                            </div>

                            <!-- Start Button -->
                            <div class="d-grid gap-2">
                                <button type="button" id="startTestBtn" class="btn btn-success btn-lg">
                                    <i class="fas fa-play me-2"></i>Start Test
                                </button>
                            </div>
                        </div>

                        <!-- Test Content Section (Hidden Initially) -->
                        <div id="test_content_section" style="display: none;">
                            <!-- Test questions will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Latest Result Card -->
               {{-- <div class="card mt-4" id="latest_result_card" style="display: none;">
                    <div class="card-header">
                        <h6 class="fw-semibold">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Your Latest Result
                        </h6>
                    </div>
                    <div class="card-body" id="latest_result_content">
                        <div class="text-center py-3">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>  --}}
            </div>

            <!-- Right Column - Test List -->
            <div class="col-lg-4">
                <div class="card fade-in">
                    <div class="card-header">
                        <h6 class="fw-semibold">
                            <i class="fas fa-list-ol me-2 text-primary"></i>Available Tests
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div id="test_list">
                            @if($preselectCourseId && count($qPapers) > 0)
                                @foreach($qPapers as $index => $paper)
                                    <a href="javascript:void(0)"
                                       class="test-item {{ $index == 0 ? 'active' : '' }}"
                                       data-index="{{ $index }}"
                                       onclick="selectTest({{ $index }})">
                                        <h6 class="fw-semibold">
                                            <i class="fas fa-clipboard-list me-2"></i>{{ $paper->question_paper_name }}
                                        </h6>
                                        <div class="row mt-2 g-2 small text-muted">
                                            <div class="col-6"><i class="fas fa-question-circle me-1"></i>{{ $paper->total_questions }} Questions</div>
                                            <div class="col-6"><i class="fas fa-star me-1"></i>{{ $paper->total_marks }} Marks</div>
                                            <div class="col-6"><i class="fas fa-calendar me-1"></i>{{ 
                                                $paper->start_date?date_create($paper->start_date)->format('d-m-Y'):''
                                            }}</div>
                                            {{--<div class="col-6"><i class="far fa-clock me-1"></i>{{ $paper->duration ? $paper->duration.' mins' : 'No limit' }}</div>--}}
                                            <div class="col-6"><i class="far fa-clock me-1"></i>{{ 
                                                $paper->start_time?\Carbon\Carbon::parse($paper->start_time)->format('h:i A'):'';
                                            }}-{{$paper->end_time?\Carbon\Carbon::parse($paper->end_time)->format('h:i A'):'';}}
                                        </div>
                                            
                                            <div class="col-6"> &nbsp; </div>
                                            <div class="col-6 text-end">
                                                @if($paper->attempt_count > 0)
                                                    <span class="badge">{{ $paper->attempt_count }} Attempt{{ $paper->attempt_count > 1 ? 's' : '' }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($paper->description)
                                            <p class="mb-0 mt-2 small text-muted">{{ $paper->description }}</p>
                                        @endif
                                    </a>
                                @endforeach
                            @elseif($preselectCourseId)
                                <div class="empty-block">
                                    <i class="fas fa-file-alt fs-1 mb-3 d-block"></i>
                                    <p>No mock tests available for this course</p>
                                </div>
                            @else
                                <div class="empty-block">
                                    <i class="fas fa-clipboard-check fs-1 mb-3 d-block"></i>
                                    <p>Please select a course to view mock tests</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Server-bootstrapped question papers (when arriving with a preselected course)
window.SERVER_PAPERS = @json($qPapers ?? []);
window.PRESELECT_COURSE_ID = @json($preselectCourseId ?? null);

// Mock Test Page JavaScript
(function() {
    'use strict';

    // Global variables
    let currentCourseId = null;
    let currentExamSectionId = null;
    let currentTests = [];
    let selectedTestId = null;

    // Wait for DOM to load
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_select');
        const examSectionSelect = document.getElementById('exam_section_select');

        // If the page was rendered with a preselected course AND server papers,
        // skip the exam-section flow entirely — list & first-paper details are
        // already in the DOM. Just hydrate the JS state.
        if (window.PRESELECT_COURSE_ID && Array.isArray(window.SERVER_PAPERS) && window.SERVER_PAPERS.length > 0) {
            currentCourseId = window.PRESELECT_COURSE_ID;
            currentTests = window.SERVER_PAPERS;
            // The first .test-item is already marked .active in Blade; populate details.
            selectTest(0);
        } else if (courseSelect && courseSelect.value) {
            currentCourseId = courseSelect.value;
            loadExamSections();
        }

        if (courseSelect) {
            courseSelect.addEventListener('change', function() {
                const selectedCourseId = this.value;
                if (selectedCourseId) {
                    currentCourseId = selectedCourseId;
                    currentExamSectionId = null;
                    loadExamSections();
                } else {
                    examSectionSelect.innerHTML = '<option value="">First select a course</option>';
                    examSectionSelect.disabled = true;
                    document.getElementById('test_list').innerHTML = `
                        <div class="empty-block">
                            <i class="fas fa-graduation-cap fs-1 mb-3 d-block"></i>
                            <p>Please select a course to view mock tests</p>
                        </div>
                    `;
                    document.getElementById('test_section').style.display = 'none';
                    document.getElementById('no_test_selected').style.display = 'block';
                    currentTests = [];
                    selectedTestId = null;
                }
            });
        }


        if (examSectionSelect) {
            examSectionSelect.addEventListener('change', function() {
                const selectedExamSectionId = this.value;
                currentExamSectionId = selectedExamSectionId;
                if (currentCourseId && selectedExamSectionId) {
                    loadAllQuestionPapers();
                } else {
                    document.getElementById('test_list').innerHTML = `
                        <div class="empty-block">
                            <i class="fas fa-list-alt fs-1 mb-3 d-block"></i>
                            <p>Please select an exam section to view mock tests</p>
                        </div>
                    `;
                    document.getElementById('test_section').style.display = 'none';
                    currentTests = [];
                    selectedTestId = null;
                }
            });
        }
    });

    
    function loadExamSections() {
        const examSectionSelect = document.getElementById('exam_section_select');
        examSectionSelect.innerHTML = '<option value="">Loading...</option>';
        examSectionSelect.disabled = true;

        document.getElementById('test_list').innerHTML = `
            <div class="empty-block">
                <i class="fas fa-list-alt fs-1 mb-3 d-block"></i>
                <p>Please select an exam section to view mock tests</p>
            </div>
        `;
        document.getElementById('test_section').style.display = 'none';
        currentTests = [];
        selectedTestId = null;

        fetch('{{ route("student.get-exam-sections") }}?course_id=' + currentCourseId)
            .then(response => response.json())
            .then(examSections => {
                let options = '<option value="">Choose an exam section...</option>';
                if (examSections.length > 0) {
                    examSections.forEach(section => {
                        options += `<option value="${section.id}">${section.tab_heading}</option>`;
                    });
                    examSectionSelect.innerHTML = options;
                    examSectionSelect.disabled = false;
                } else {
                    examSectionSelect.innerHTML = '<option value="">No exam sections available</option>';
                    examSectionSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error loading exam sections:', error);
                examSectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                examSectionSelect.disabled = true;
            });
    }

    function loadAllQuestionPapers() {
        if (!currentCourseId || !currentExamSectionId) return;

        const testList = document.getElementById('test_list');
        testList.innerHTML = `
            <div class="empty-block">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Loading mock tests...</p>
            </div>
        `;

        document.getElementById('no_test_selected').style.display = 'none';

        let queryParams = 'course_id=' + currentCourseId + '&exam_section_id=' + currentExamSectionId;

        fetch('{{ route("student.get-question-papers") }}?' + queryParams)
            .then(response => response.json())
            .then(questionPapers => {
                currentTests = questionPapers;
                if (questionPapers && questionPapers.length > 0) {
                    displayTestList(questionPapers);
                    selectTest(0);
                } else {
                    testList.innerHTML = `
                        <div class="empty-block">
                            <i class="fas fa-file-alt fs-1 mb-3 d-block"></i>
                            <p>No mock tests available for this section</p>
                        </div>
                    `;
                    document.getElementById('no_test_selected').style.display = 'block';
                    document.getElementById('test_section').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading question papers:', error);
                testList.innerHTML = `
                    <div class="empty-block">
                        <i class="fas fa-exclamation-triangle fs-1 mb-3 d-block" style="color:#f43f5e;"></i>
                        <p>Error loading mock tests</p>
                    </div>
                `;
                document.getElementById('no_test_selected').style.display = 'block';
                document.getElementById('test_section').style.display = 'none';
            });
    }
    

    function displayTestList(tests) {
        const testList = document.getElementById('test_list');
        let html = '';

        tests.forEach((test, index) => {
            const duration = test.duration ? test.duration + ' mins' : 'No limit';
            html += `
                <a href="javascript:void(0)" class="test-item" data-index="${index}" onclick="selectTest(${index})">
                    <h6 class="fw-semibold">
                        <i class="fas fa-clipboard-list me-2"></i>${test.question_paper_name}
                    </h6>
                    <div class="row mt-2 g-2 small text-muted">
                        <div class="col-6"><i class="fas fa-question-circle me-1"></i>${test.total_questions} Questions</div>
                        <div class="col-6"><i class="far fa-clock me-1"></i>${duration}</div>
                        <div class="col-6"><i class="fas fa-star me-1"></i>${test.total_marks} Marks</div>
                        <div class="col-6 text-end">
                            ${test.attempt_count > 0 ? `<span class="badge">${test.attempt_count} Attempt${test.attempt_count > 1 ? 's' : ''}</span>` : ''}
                        </div>
                    </div>
                    ${test.description ? `<p class="mb-0 mt-2 small text-muted">${test.description}</p>` : ''}
                </a>
            `;
        });

        testList.innerHTML = html;
    }

    window.selectTest = function(index) {
        if (!currentTests[index]) return;

        selectedTestId = currentTests[index].id;
        window.selectedTestId = selectedTestId; // expose for the jQuery Start Test handler
        const test = currentTests[index];

        document.querySelectorAll('.test-item').forEach(item => item.classList.remove('active'));
        const currentItem = document.querySelector(`.test-item[data-index="${index}"]`);
        if (currentItem) currentItem.classList.add('active');

        document.getElementById('current_test_title').textContent = test.question_paper_name;
        document.getElementById('total_questions').textContent = test.total_questions || 0;
        document.getElementById('test_duration').textContent = test.duration ? test.duration + ' mins' : 'No limit';
                        
        //document.getElementById('total_marks').textContent = test.total_marks+10 || 0;

        var stime=moment(test.start_time, "hh:mm:ss").format("h:mm A") || '';
        var etime=moment(test.end_time, "hh:mm:ss").format("h:mm A") || '';

        document.getElementById('total_marks').textContent = stime+"-"+etime || "--";

        document.getElementById('start__date').textContent = moment(test.start_date).format("DD-MM-YYYY") || '';
        document.getElementById('start__time').textContent = moment(test.start_time, "hh:mm:ss").format("hh:mm A") || '';

        document.getElementById('test_section').style.display = 'block';
        document.getElementById('no_test_selected').style.display = 'none';

        loadLatestResult(selectedTestId);

        if (currentItem) currentItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    function loadLatestResult(questionPaperId) {
        const resultCard = document.getElementById('latest_result_card');
        const resultContent = document.getElementById('latest_result_content');

        resultCard.style.display = 'block';
        resultContent.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        fetch('/get-latest-result-id/' + questionPaperId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.result) {
                    displayLatestResult(data.result, data.result_id);
                } else {
                    resultCard.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading result:', error);
                resultCard.style.display = 'none';
            });
    }

    function displayLatestResult(result, resultId) {
        const resultContent = document.getElementById('latest_result_content');
        const percentage = result.total_questions > 0 ? ((result.answer / result.total_questions) * 100).toFixed(2) : 0;

        const html = `
            <div class="row g-3">
                <div class="col-6 col-md">
                    <div class="result-tile score">
                        <i class="fas fa-star"></i>
                        <h5>${result.score}</h5>
                        <small>Score</small>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="result-tile pct">
                        <i class="fas fa-percent"></i>
                        <h5>${percentage}%</h5>
                        <small>Percentage</small>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="result-tile correct">
                        <i class="fas fa-check-circle"></i>
                        <h5>${result.answer}</h5>
                        <small>Correct</small>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="result-tile wrong">
                        <i class="fas fa-times-circle"></i>
                        <h5>${result.wrong}</h5>
                        <small>Wrong</small>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="result-tile skip">
                        <i class="fas fa-minus-circle"></i>
                        <h5>${result.skipped}</h5>
                        <small>Skipped</small>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="/test-result/${resultId}" class="btn btn-primary">
                    <i class="fas fa-chart-line me-2"></i>View Detailed Result
                </a>
            </div>
        `;

        resultContent.innerHTML = html;
    }
    
    window.loadAllQuestionPapers = loadAllQuestionPapers;
    window.loadExamSections = loadExamSections;
    window.displayTestList = displayTestList;
    window.selectTest = selectTest;

})();

/* ===== jQuery-based Start Test handler =====
   Intentionally OUTSIDE the IIFE above so it has direct jQuery access. The
   button (#startTestBtn) is type="button" so the click never has any default
   navigation. We only redirect to /take-test/{id} inside the AJAX success
   branch where data.status === 'ok'. Every other branch returns without
   navigating. */
jQuery(function ($) {
    $(document).on('click', '#startTestBtn', function (e) {
        e.preventDefault();

        var testId = (typeof selectedTestId !== 'undefined' && selectedTestId)
                        ? selectedTestId
                        : (window.selectedTestId || null);

        if (!testId) {
            Swal.fire({
                icon: 'warning',
                title: 'No Test Selected',
                text: 'Please select a test first.',
                confirmButtonColor: '#f7931e'
            });
            return false;
        }

        var $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
            url: '/validate-test-time/' + testId,
            type: 'GET',
            dataType: 'json',
            cache: false,
            headers: { 'Accept': 'application/json' }
        })
        .done(function (data) {
            if (!data || data.status === 'invalid') {
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to Start',
                    text: (data && data.message) ? data.message : 'Could not validate the test schedule.',
                    confirmButtonColor: '#f7931e'
                });
                return;
            }

            if (data.status === 'attended') {
                Swal.fire({
                    icon: 'info',
                    title: 'Already Attended',
                    text: data.message,
                    confirmButtonColor: '#f7931e'
                });
                return;
            }

            if (data.status === 'not_started') {
                Swal.fire({
                    icon: 'info',
                    title: 'Test Not Started Yet',
                    html: '<p>' + data.message + '</p>'
                        + '<small class="text-muted">Current time: ' + data.now_display + ' (IST)</small>',
                    confirmButtonColor: '#f7931e'
                });
                return;
            }

            if (data.status === 'ended') {
                Swal.fire({
                    icon: 'error',
                    title: 'Test Window Closed',
                    html: '<p>' + data.message + '</p>'
                        + '<small class="text-muted">Current Time: ' + data.now_display + ' (IST)</small>',
                    confirmButtonColor: '#f7931e'
                });
                return;
            }

            if (data.status === 'ok') {
                window.location.href = '/take-test/' + testId;
                return;
            }

            // Unknown status — be safe, don't navigate.
            Swal.fire({
                icon: 'error',
                title: 'Unexpected Response',
                text: 'Unexpected status: ' + data.status,
                confirmButtonColor: '#f7931e'
            });
        })
        .fail(function (xhr) {
            var msg = 'Could not reach the server. Please try again.';
            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: msg,
                confirmButtonColor: '#f7931e'
            });
        })
        .always(function () {
            $btn.prop('disabled', false);
        });

        return false;
    });
});
</script>
@endpush
