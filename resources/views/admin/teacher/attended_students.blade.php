@extends('admin.layouts.master')
@section('title','Attended Students')
@section('contents')

<style>
    .as-wrap { padding: 8px 4px 24px; }

    .as-head {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 60%, #cffafe 100%);
        border: 1px solid #bfdbfe;
        border-radius: 16px;
        padding: 20px 24px;
        color: #0c4a6e;
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }
    .as-head h1 { font-size: 1.3rem; font-weight: 500; margin: 0 0 4px; color: #0c4a6e; letter-spacing: -0.3px; }
    .as-head .meta { font-size: 0.85rem; color: #1e40af; opacity: 0.9; }
    .as-head .meta i { color: #3b82f6; margin-right: 4px; }
    .as-head a.back {
        background: #fff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        padding: 6px 14px; border-radius: 999px;
        font-size: 0.82rem; 
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        white-space: nowrap;
    }
    .as-head a.back:hover { background: #dbeafe; }

    .as-stats {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;
        margin-bottom: 18px;
    }
    @media (max-width: 767px) { .as-stats { grid-template-columns: repeat(2, 1fr); } }
    .as-stat {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 12px 14px;
        display: flex; align-items: center; gap: 12px;
    }
    .as-stat .ic {
        width: 36px; height: 36px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        flex: 0 0 auto;
    }
    .as-stat .ic.blue   { background: rgba(59,130,246,0.12); color: #2563eb; }
    .as-stat .ic.green  { background: rgba(34,197,94,0.12); color: #16a34a; }
    .as-stat .ic.amber  { background: rgba(245,158,11,0.12); color: #d97706; }
    .as-stat .ic.violet { background: rgba(139,92,246,0.12); color: #7c3aed; }
    .as-stat .lbl { flex: 1; font-size: 0.8rem; color: #64748b; }
    .as-stat .val { font-size: 1.2rem; font-weight: 700; color: #0f172a; }

    .as-panel {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; padding: 18px;
    }
    .as-panel-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px;
    }
    .as-panel-title { font-size: 1rem; font-weight: 500; color: #0f172a; margin: 0; }

    .as-table { width: 100%; border-collapse: collapse; font-size: 0.86rem; color: #0f172a; }
    .as-table thead th {
        text-align: left; font-weight: 600; font-size: 0.74rem;
        color: #64748b; background: #f8fafc; padding: 10px 14px;
        border-bottom: 1px solid #e5e7eb; text-transform: uppercase; letter-spacing: 0.4px;
        white-space: nowrap;
    }
    .as-table tbody td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .as-table tbody tr:last-child td { border-bottom: 0; }
    .as-table tbody tr:hover { background: #f8fafc; }
    .as-table .right { text-align: right; }
    .as-table .num { font-variant-numeric: tabular-nums; }
    .as-table .stu {
        display: inline-flex; align-items: center; gap: 8px;
    }
    .as-table .stu .avt {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
        color: #fff; font-weight: 700; font-size: 0.78rem;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .as-table .stu .nm { font-weight: 400; }
    .as-table .stu .sub { display: block; font-size: 0.72rem; color: #94a3b8; }

    .pill-tag {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 999px;
        font-size: 0.74rem; font-weight: 600;
    }
    .pill-tag.green  { background: rgba(34,197,94,0.12); color: #16a34a; }
    .pill-tag.red    { background: rgba(244,63,94,0.12); color: #e11d48; }
    .pill-tag.amber  { background: rgba(245,158,11,0.14); color: #d97706; }

    .empty-row td {
        text-align: center; padding: 28px 14px; color: #94a3b8; font-size: 0.92rem;
    }

    /* Evaluate button + modal */
    .btn-evaluate {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 100%);
        border: 1px solid #c7d2fe;
        color: #4338ca;
        padding: 5px 14px; border-radius: 999px;
        font-size: 0.78rem; font-weight: 500;
        cursor: pointer;
        transition: transform 0.12s ease, box-shadow 0.12s ease;
    }
    .btn-evaluate:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(99,102,241,0.18);
    }
    .btn-evaluate i { color: #4338ca; font-size: 0.9rem; }
    .btn-evaluate:hover i { color: #312e81; }
    .desc-mark-cell { font-weight: 700; color: #0f172a; }

    .ev-modal-body { padding: 18px 22px; }
    .ev-progress {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 12px;
    }
    .ev-progress .step {
        font-size: 0.82rem; color: #64748b; font-weight: 600;
    }
    .ev-progress .step strong { color: #0f172a; }
    .ev-progress .total {
        background: rgba(34,197,94,0.12); color: #16a34a;
        padding: 4px 12px; border-radius: 999px;
        font-size: 0.78rem; font-weight: 700;
    }
    .ev-question {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.94rem;
        color: #0f172a;
        line-height: 1.5;
        margin-bottom: 12px;
    }
    .ev-label {
        font-size: 0.78rem; font-weight: 700; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.4px;
        margin: 14px 0 6px;
    }
    .ev-answer {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 0.92rem;
        color: #334155;
        line-height: 1.5;
        white-space: pre-wrap;
        min-height: 80px;
    }
    .ev-answer.empty { color: #94a3b8; font-style: italic; }
    .ev-mark-row { display: flex; align-items: center; gap: 12px; margin-top: 14px; }
    .ev-mark-row label { font-size: 0.86rem; font-weight: 600; color: #0f172a; margin: 0; }
    .ev-mark-row input[type=number] {
        width: 120px;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.95rem;
        background: #f8fafc;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .ev-mark-row input[type=number]:focus {
        outline: none;
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.12);
    }
    .ev-nav { display: flex; gap: 10px; justify-content: space-between; align-items: center; }
    .ev-nav .left, .ev-nav .right { display: flex; gap: 8px; }
    .btn-ev {
        padding: 7px 16px; border-radius: 10px; border: 1px solid #c7d2fe;
        background: #fff; color: #4338ca; font-weight: 600; font-size: 0.85rem;
        cursor: pointer;
    }
    .btn-ev:hover { background: #eef2ff; }
    .btn-ev:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-ev.primary { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: #fff; border: 0; }
    .btn-ev.primary:hover { box-shadow: 0 8px 18px rgba(99,102,241,0.3); }
</style>

<div class="as-wrap">

    {{-- ===== Page header ===== --}}
    <div class="as-head">
        <div>
            <h1>{{ $paper->question_paper_name }}</h1>
            <div class="meta">
                <span><i class="fa fa-book"></i>{{ $paper->course_name ?? '-' }}</span>
                <span class="mx-2">·</span>
                <span><i class="fa fa-calendar"></i>{{ $paper->start_date ? \Carbon\Carbon::parse($paper->start_date)->format('d M Y') : '-' }}</span>
                <span class="mx-2">·</span>
                <span><i class="fa fa-clock"></i>
                    {{ $paper->start_time ? \Carbon\Carbon::parse($paper->start_time)->format('h:i A') : '-' }} —
                    {{ $paper->end_time   ? \Carbon\Carbon::parse($paper->end_time)->format('h:i A')   : '-' }}
                </span>
                @if(!empty($paper->duration))
                    <span class="mx-2">·</span>
                    <span><i class="fa fa-hourglass-half"></i>{{ $paper->duration }} min</span>
                @endif
            </div>
        </div>
        <a href="{{ route('teacher.exam-tests') }}" class="back">
            <i class="fa fa-arrow-left"></i> Back to Tests
        </a>
    </div>

    {{-- ===== Quick stats ===== --}}
    @php
        $totalAttempts = $attempts->count();
        $avgScore      = $totalAttempts > 0 ? round($attempts->avg('score'), 2) : 0;
        $highest       = $totalAttempts > 0 ? $attempts->max('score') : 0;
        $totalSkipped  = $attempts->sum('skipped');
    @endphp
    <div class="as-stats">
        <div class="as-stat">
            <span class="ic blue"><i class="fa fa-users"></i></span>
            <span class="lbl">Attended</span>
            <span class="val">{{ $totalAttempts }}</span>
        </div>
        <div class="as-stat">
            <span class="ic green"><i class="fa fa-trophy"></i></span>
            <span class="lbl">Highest Score</span>
            <span class="val">{{ $highest }}</span>
        </div>
        <div class="as-stat">
            <span class="ic violet"><i class="fa fa-chart-line"></i></span>
            <span class="lbl">Average Score</span>
            <span class="val">{{ $avgScore }}</span>
        </div>
        <div class="as-stat">
            <span class="ic amber"><i class="fa fa-minus-circle"></i></span>
            <span class="lbl">Total Skipped</span>
            <span class="val">{{ $totalSkipped }}</span>
        </div>
    </div>

    {{-- Hidden context for the evaluate modal --}}
    <input type="hidden" id="ev-paper-id" value="{{ $paper->id }}">

    {{-- ===== Attended Students Table ===== --}}
    <div class="as-panel">
        <div class="as-panel-head">
            <h3 class="as-panel-title">
                <i class="fa fa-user-check me-2" style="color:#2563eb;"></i>
                Attended Students <span style="color:#64748b;font-weight:500;">({{ $totalAttempts }})</span>
            </h3>
        </div>

            <table id="datatable" class="as-table" style="width:100% !important;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Candidate ID</th>
                        <th>Mobile</th>
                        <th>Test Date</th>
                        <th class="right num">Total Q</th>
                        <th class="right num">Correct</th>
                        <th class="right num">Wrong</th>
                        <th class="right num">Skipped</th>
                        <th class="right num">Descriptive Mark</th>
                        <th class="right num">Score</th>
                        <th class="right">Evaluate</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
    </div>

</div>

{{-- ===== Evaluate Modal ===== --}}
<div class="modal fade" id="evaluateModal" tabindex="-1" aria-labelledby="evaluateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="evaluateModalLabel">
            <i class="fa fa-pen-to-square text-primary me-2"></i>
            Evaluate Descriptive Answers — <span id="ev-student-name"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ev-modal-body">

        <div id="ev-loading" class="text-center py-4">
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            <p class="text-muted mt-2 mb-0" style="font-size:0.88rem;">Loading descriptive questions…</p>
        </div>

        <div id="ev-empty" class="text-center py-4" style="display:none;">
            <i class="fa fa-circle-info text-muted mb-2" style="font-size:1.5rem;"></i>
            <p class="text-muted mb-0">No descriptive questions for this paper.</p>
        </div>

        <div id="ev-content" style="display:none;">
            <div class="ev-progress">
                <div class="step">Question <strong id="ev-cur-no">1</strong> of <strong id="ev-total">1</strong></div>
                <div class="total">Total Awarded: <span id="ev-total-mark">0</span></div>
            </div>

            <div class="ev-label">Question</div>
            <div class="ev-question" id="ev-question-text">—</div>

            <div class="ev-label">Student's Answer</div>
            <div class="ev-answer" id="ev-answer-text">—</div>

            <div class="ev-mark-row">
                <label for="ev-mark-input">Mark <small class="text-muted">(0&nbsp;–&nbsp;3)</small>:</label>
                <input type="number" min="0" max="3" step="0.5" id="ev-mark-input" placeholder="0">
                <span id="ev-mark-error" style="color:#e11d48;font-size:0.8rem;font-weight:600;display:none;">Mark must be between 0 and 3.</span>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="ev-nav w-100">
            <div class="left">
                <button type="button" class="btn-ev" id="ev-prev"><i class="fa fa-chevron-left"></i> Previous</button>
                <button type="button" class="btn-ev" id="ev-next">Next <i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="right">
                <button type="button" class="btn-ev" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-ev primary" id="ev-submit" style="display:none;"><i class="fa fa-check"></i> Submit Marks</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
    var modal     = null;
    var questions = [];     // [{id, question, question_answer, mark}]
    var current   = 0;
    var visited   = new Set();   // question indexes the teacher has opened
    var studentId = null;
    var studentName = '';
    var paperId   = parseInt(document.getElementById('ev-paper-id').value, 10);

    function $(id) { return document.getElementById(id); }

    var MARK_MIN = 0;
    var MARK_MAX = 3;

    // Returns true if the current input is a valid number in [MARK_MIN, MARK_MAX].
    // Toggles the inline error message in either case.
    function isCurrentMarkValid() {
        var raw = $('ev-mark-input').value;
        var err = $('ev-mark-error');
        if (raw === '') { err.style.display = 'none'; return true; } // blank = 0, allowed
        var n = parseFloat(raw);
        var ok = !isNaN(n) && n >= MARK_MIN && n <= MARK_MAX;
        err.style.display = ok ? 'none' : 'inline-block';
        return ok;
    }

    function commitCurrentMark() {
        if (!questions[current]) return;
        var v = $('ev-mark-input').value;
        var n = parseFloat(v);
        if (v === '' || isNaN(n)) {
            questions[current].mark = 0;
        } else {
            // Clamp into the allowed range so the stored value is always 0-3.
            if (n < MARK_MIN) n = MARK_MIN;
            if (n > MARK_MAX) n = MARK_MAX;
            questions[current].mark = n;
        }
    }

    function totalMark() {
        return questions.reduce(function (acc, q) {
            var m = (q.mark === null || q.mark === '' || isNaN(parseFloat(q.mark))) ? 0 : parseFloat(q.mark);
            return acc + m;
        }, 0);
    }

    function render() {
        if (!questions.length) {
            $('ev-content').style.display = 'none';
            $('ev-empty').style.display = 'block';
            // No questions to grade — nothing to submit either.
            $('ev-submit').style.display = 'none';
            return;
        }
        $('ev-empty').style.display = 'none';
        $('ev-content').style.display = 'block';

        // Track that this question has been viewed.
        visited.add(current);

        var q = questions[current];
        $('ev-cur-no').textContent = (current + 1);
        $('ev-total').textContent  = questions.length;
        $('ev-question-text').innerHTML = q.question || '—';

        var ansEl = $('ev-answer-text');
        if (q.question_answer && q.question_answer.trim() !== '') {
            ansEl.classList.remove('empty');
            ansEl.textContent = q.question_answer;
        } else {
            ansEl.classList.add('empty');
            ansEl.textContent = 'Student did not provide an answer.';
        }

        var mi = $('ev-mark-input');
        mi.value = (q.mark === null || q.mark === undefined || q.mark === '') ? '' : q.mark;
        mi.focus();

        $('ev-prev').disabled = (current === 0);
        $('ev-next').disabled = (current === questions.length - 1);
        $('ev-total-mark').textContent = totalMark();

        // Show Submit only once the teacher has opened EVERY question.
        if (visited.size >= questions.length) {
            $('ev-submit').style.display = '';
        } else {
            $('ev-submit').style.display = 'none';
        }
    }

    function openEvaluate(stuId, stuName) {
        studentId = stuId;
        studentName = stuName || '';
        $('ev-student-name').textContent = studentName;
        $('ev-loading').style.display = 'block';
        $('ev-content').style.display = 'none';
        $('ev-empty').style.display = 'none';
        $('ev-submit').style.display = 'none';   // hidden until all questions visited
        visited = new Set();                     // reset tracker per student
        modal = new bootstrap.Modal(document.getElementById('evaluateModal'));
        modal.show();

        fetch('/teacher/descriptive-questions/' + paperId + '/' + studentId, {
            headers: { 'Accept': 'application/json' },
            cache: 'no-store'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            $('ev-loading').style.display = 'none';
            questions = (data && data.questions) ? data.questions : [];
            current = 0;
            render();
        })
        .catch(function () {
            $('ev-loading').style.display = 'none';
            alert('Could not load descriptive questions. Please try again.');
        });
    }

    // Initialize the Attended Students DataTable (server-side via yajra).
    var dt = null;
    if (window.jQuery && jQuery.fn.DataTable) {
        dt = jQuery('#datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            scrollX: true,
            pagingType: 'simple_numbers',
            order: [[10, 'desc']], // sort by Score desc by default
            ajax: {
                url: "{{ route('teacher.attended-students-data', $paper->id) }}",
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex',     orderable: false, searchable: false },
                { data: 'student',         orderable: false, searchable: false },
                { data: 'candidate_id',    orderable: false },
                { data: 'mobile',          orderable: false },
                { data: 'test_date',       orderable: true,  searchable: false },
                { data: 'total_questions', orderable: false, searchable: false, className: 'right num' },
                { data: 'correct_pill',    orderable: false, searchable: false, className: 'right num' },
                { data: 'wrong_pill',      orderable: false, searchable: false, className: 'right num' },
                { data: 'skipped_pill',    orderable: false, searchable: false, className: 'right num' },
                { data: 'desc_mark',       orderable: false, searchable: false, className: 'right num' },
                { data: 'score',           orderable: true,  searchable: false, className: 'right num' },
                { data: 'evaluate',        orderable: false, searchable: false, className: 'right' }
            ],
            language: {
                emptyTable: 'No students have attended this test yet.'
            }
        });
    }
    window.__attendedDt = dt; // exposed so the save handler can reload it

    // Delegated click handler — survives DataTables paging / sort / reload.
    jQuery(document).on('click', '#datatable .btn-evaluate', function () {
        openEvaluate(
            parseInt(jQuery(this).data('student-id'), 10),
            jQuery(this).data('student-name') || ''
        );
    });

    // Live update of total as the teacher types — also validates range.
    $('ev-mark-input').addEventListener('input', function () {
        isCurrentMarkValid();
        commitCurrentMark();
        $('ev-total-mark').textContent = totalMark();
    });

    $('ev-prev').addEventListener('click', function () {
        if (!isCurrentMarkValid()) return; // refuse to leave invalid value
        commitCurrentMark();
        if (current > 0) { current--; render(); }
    });
    $('ev-next').addEventListener('click', function () {
        if (!isCurrentMarkValid()) return;
        commitCurrentMark();
        if (current < questions.length - 1) { current++; render(); }
    });

    $('ev-submit').addEventListener('click', function () {
        if (!isCurrentMarkValid()) return;
        commitCurrentMark();

        var payload = {
            question_paper_id: paperId,
            student_id: studentId,
            marks: questions.map(function (q) {
                return { question_id: (q.question_id ?? q.id), mark: (q.mark === null || q.mark === '') ? 0 : q.mark };
            })
        };

        $('ev-submit').disabled = true;
        $('ev-submit').innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving…';

        fetch('/teacher/save-descriptive-marks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, body: j }; }); })
        .then(function (res) {
            $('ev-submit').disabled = false;
            $('ev-submit').innerHTML = '<i class="fa fa-check"></i> Submit Marks';

            if (res.ok && res.body && res.body.status) {
                // Reload the DataTable so the row reflects the new values
                // (desc mark, score, "Evaluated" pill) — falls back to plain
                // DOM updates in case the table isn't a DataTable.
                if (window.__attendedDt) {
                    window.__attendedDt.ajax.reload(null, false);
                } else {
                    var cell = document.getElementById('desc-mark-' + studentId);
                    if (cell) cell.textContent = res.body.total;
                    if (res.body.score !== null && res.body.score !== undefined) {
                        var sc = document.getElementById('score-cell-' + studentId);
                        if (sc) sc.textContent = res.body.score;
                    }
                    var evCell = document.getElementById('evaluate-cell-' + studentId);
                    if (evCell) {
                        evCell.innerHTML = '<span class="pill-tag green"><i class="fa fa-check-circle"></i> Evaluated</span>';
                    }
                }
                modal.hide();
                if (typeof toastr !== 'undefined') {
                    toastr.success(res.body.message);
                } else {
                    alert(res.body.message);
                }
            } else {
                var msg = (res.body && res.body.message) ? res.body.message : 'Failed to save marks.';
                if (typeof toastr !== 'undefined') toastr.error(msg); else alert(msg);
            }
        })
        .catch(function () {
            $('ev-submit').disabled = false;
            $('ev-submit').innerHTML = '<i class="fa fa-check"></i> Submit Marks';
            alert('Network error — please try again.');
        });
    });
})();
</script>
@endpush

@endsection
