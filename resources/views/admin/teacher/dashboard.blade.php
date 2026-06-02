@extends('admin.layouts.master')
@section('title','Teacher Dashboard')
@section('contents')

{{-- ===== Styles (mirrors student dashboard look, light theme for admin) ===== --}}
<style>
    .tch-wrapper { padding: 8px 4px 24px; }

    /* Hero */
    .tch-hero {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 60%, #cffafe 100%);
        border: 1px solid #bfdbfe;
        border-radius: 18px;
        padding: 26px 28px;
        color: #0c4a6e;
        display: flex; align-items: center; justify-content: space-between;
        gap: 18px;
        box-shadow: 0 8px 22px rgba(59, 130, 246, 0.10);
    }
    .tch-hero h1 { font-size: 1.55rem; font-weight: 500; margin: 0 0 4px; letter-spacing: -0.4px; color: #0c4a6e; }
    .tch-hero p  { margin: 0; color: #1e40af; opacity: 0.85; font-size: 0.92rem; }
    .tch-pill {
        display: inline-flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.7);
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        padding: 6px 14px 6px 6px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .tch-pill .avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.95rem;
    }
    .tch-pill .pill-name { font-size: 0.92rem;  line-height: 1.1; color: #0f172a; }
    .tch-pill .pill-role { font-size: 0.72rem; color: #64748b; }

    /* Stat cards (single-line: icon | label | value) */
    .tch-stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 22px; }
    @media (max-width: 991px) { .tch-stat-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 420px) { .tch-stat-row { grid-template-columns: 1fr; } }

    .tch-stat {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex; align-items: center; gap: 14px;
        min-height: 64px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .tch-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15,23,42,0.06); }
    .tch-stat .ic {
        width: 40px; height: 40px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.05rem; flex: 0 0 auto;
    }
    .tch-stat .ic.green  { background: rgba(34,197,94,0.12);  color: #16a34a; }
    .tch-stat .ic.blue   { background: rgba(59,130,246,0.12); color: #2563eb; }
    .tch-stat .ic.amber  { background: rgba(245,158,11,0.12); color: #d97706; }
    .tch-stat .ic.violet { background: rgba(139,92,246,0.12); color: #7c3aed; }
    .tch-stat .lbl {
        flex: 1 1 auto; font-size: 0.85rem; color: #64748b; margin: 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .tch-stat .val { flex: 0 0 auto; font-size: 1.35rem; font-weight: 500; color: #0f172a; line-height: 1; }

    /* Panels */
    .tch-grid { display: grid; grid-template-columns: 1fr 360px; gap: 18px; margin-top: 22px; }
    @media (max-width: 991px) { .tch-grid { grid-template-columns: 1fr; } }
    .tch-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 20px;
    }
    .tch-panel-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .tch-panel-title { font-size: 1.02rem; font-weight: 500; color: #0f172a; margin: 0; }
    .tch-panel-link { color: #4f46e5; font-size: 0.82rem; font-weight: 500; text-decoration: none; }

    /* Continue Teaching cards */
    .ct-list { display: flex; flex-direction: column; gap: 12px; }
    .ct-card {
        display: flex; gap: 14px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 14px;
        transition: background 0.15s ease, border-color 0.15s ease;
    }
    .ct-card:hover { background: #eef2ff; border-color: #c7d2fe; }
    .ct-thumb {
        width: 56px; height: 56px; border-radius: 12px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #fff; display: inline-flex; align-items: center; justify-content: center;
        flex: 0 0 auto;
    }
    .ct-body { flex: 1; min-width: 0; }
    .ct-title { font-size: 0.95rem; font-weight: 500; color: #0f172a; margin: 0 0 4px; }
    .ct-meta {
        color: #64748b; font-size: 0.8rem;
        display: flex; flex-wrap: wrap; align-items: center; gap: 4px;
        margin-bottom: 8px;
    }
    .ct-meta i { color: #6366f1; margin-right: 4px; }
    .ct-status { margin-left: auto; }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 999px;
        font-size: 0.74rem; font-weight: 600;
    }
    .badge-pill.active  { background: rgba(34,197,94,0.12); color: #16a34a; }
    .badge-pill.expired { background: rgba(244,63,94,0.12); color: #e11d48; }
    .ct-progress { height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
    .ct-progress-bar { height: 100%; background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%); }
    .ct-progress-meta {
        display: flex; justify-content: space-between;
        font-size: 0.76rem; color: #64748b; margin-top: 4px;
    }
    .ct-progress-meta .pct { color: #4f46e5; font-weight: 700; }

    /* Upcoming list */
    .uc-item { display: flex; gap: 12px; align-items: flex-start; padding: 10px 0; border-bottom: 1px dashed #e5e7eb; }
    .uc-item:last-child { border-bottom: 0; }
    .uc-num {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(99,102,241,0.12); color: #4f46e5;
        display: inline-flex; flex-direction: column; align-items: center; justify-content: center;
        font-size: 0.85rem; font-weight: 700; flex: 0 0 auto; line-height: 1;
    }
    .uc-num .lbl { font-size: 0.62rem; font-weight: 600; opacity: 0.7; }
    .uc-title { margin: 0 0 2px; font-size: 0.92rem; font-weight: 600; color: #0f172a; }
    .uc-desc  { margin: 0; font-size: 0.78rem; color: #64748b; }

    /* Mock tests table */
    .mt-panel { margin-top: 22px; }
    .mt-wrap { overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 12px; }
    .mt-table { width: 100%; border-collapse: collapse; font-size: 0.86rem; color: #0f172a; }
    .mt-table thead th {
        text-align: left; font-weight: 600; font-size: 0.74rem;
        color: #64748b; background: #f8fafc; padding: 11px 14px;
        border-bottom: 1px solid #e5e7eb; text-transform: uppercase; letter-spacing: 0.4px;
    }
    .mt-table tbody td { padding: 11px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .mt-table tbody tr:last-child td { border-bottom: 0; }
    .mt-table .right { text-align: right; }
    .btn-soft {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 100%);
        border: 1px solid #c7d2fe;
        color: #4338ca;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.78rem;
        text-decoration: none;
    }

    /* Status pills (On Going / Closed / Upcoming) */
    .status-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 999px;
        font-size: 0.74rem; font-weight: 600;
    }
    .status-pill.green { background: rgba(34,197,94,0.12);  color: #16a34a; }
    .status-pill.red   { background: rgba(244,63,94,0.12);  color: #e11d48; }
    .status-pill.amber { background: rgba(245,158,11,0.14); color: #d97706; }
</style>

<div class="tch-wrapper">

    {{-- ===== Hero ===== --}}
    <div class="tch-hero">
        <div>
            <h1>Welcome back, {{ $teacher->name }} 👋</h1>
            <p>Here's a quick look at your classes, students and upcoming sessions.</p>
        </div>
        <div class="tch-pill">
            <span class="avatar">{{ strtoupper(substr($teacher->name ?? 'T', 0, 1)) }}</span>
            <div>
                <div class="pill-name">{{ $teacher->name }}</div>
                <div class="pill-role">{{ $teacher->designation }}</div>
            </div>
        </div>
    </div>

    {{-- ===== Stat cards ===== --}}
    <div class="tch-stat-row">
        @foreach($stats as $s)
            <div class="tch-stat">
                <span class="ic {{ $s->color }}"><i class="fa {{ $s->icon }}"></i></span>
                <span class="lbl">{{ $s->label }}</span>
                <span class="val">{{ $s->value }}</span>
            </div>
        @endforeach
    </div>

    {{-- ===== Mock tests table ===== --}}
    <div class="tch-panel mt-panel">
        <div class="tch-panel-head">
            <h3 class="tch-panel-title"><i class="fa fa-file-alt me-2" style="color:#7c3aed;"></i>Exams - Question Papers List (<span style="font-size:12px;color: #7c3aed;">last 10 Nos</span>)</h3>
            <a href="{{ route('teacher.exam-tests') }}" class="tch-panel-link">View All</a>
        </div>
        <div class="mt-wrap">
            <table class="mt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Q-Paper-Id</th>
                        <th>Question Paper</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Attended</th>
                        <th class="right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mockTests as $i => $mt)


                                @php
                                    $__today = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d');
                                    $__start = $mt->start_date ? \Carbon\Carbon::parse($mt->start_date)->format('Y-m-d') : null;
     
                                    $exam_time = null;
                                    if (!empty($mt->start_date) && !empty($mt->end_time)) {
                                        $exam_time = \Carbon\Carbon::parse(
                                            \Carbon\Carbon::parse($mt->start_date)->format('Y-m-d') . ' ' . $mt->end_time,
                                            'Asia/Kolkata'
                                        );
                                    }
                                    $current_time = \Carbon\Carbon::now('Asia/Kolkata');
                                    $exam_ended   = $exam_time && $current_time->gt($exam_time);
                                @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                             <td>{{ $mt->id}}</td>
                            <td>{{ $mt->name }}</td>
                            <td>{{ $mt->course_name }}</td>
                            <td>{{ $mt->start_date ? \Carbon\Carbon::parse($mt->start_date)->format('d M Y') : '-' }}</td>
                            <td>
                                {{ $mt->start_time ? \Carbon\Carbon::parse($mt->start_time)->format('h:i A') : '-' }}
                                —
                                {{ $mt->end_time ? \Carbon\Carbon::parse($mt->end_time)->format('h:i A') : '-' }}
                            </td>
                            <td>
                         
                                @if(!$__start)
                                    <span class="status-pill amber">—</span>
                                @elseif($__start === $__today)
                                    {{-- start == today : decide between Closed / On Going by end_time vs now --}}
                                    @if($exam_time && $exam_time->lt($current_time))
                                        {{-- end_time is still in the future today --}}
                                        <span class="status-pill red"><i class="fa fa-lock"></i> Closed</span>
                                    @else
                                        <span class="status-pill green"><i class="fa fa-play"></i> On Going</span>
                                    @endif
                                @elseif($__start < $__today)
                                    <span class="status-pill red"><i class="fa fa-lock"></i> Closed</span>
                                @else
                                    <span class="status-pill amber"><i class="fa fa-clock"></i> Upcoming</span>
                                @endif
                            </td>
                            <td>{{ $mt->attended }} </td>
                            <td class="right">
  
                                @if($mt->attended != 0 && $exam_ended)
                                    <a href="{{ route('teacher.attended-students', $mt->id) }}"
                                       rel="noopener"
                                       class="btn-soft" title="View attended students">
                                        <i class="fa fa-eye"></i> View Students
                                    </a>
                                @elseif($exam_ended)
                                    <span class="status-pill amber" title="Exam ended, no submissions">
                                        <i class="fa fa-info-circle"></i> No Submissions
                                    </span>
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">No mock tests added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
