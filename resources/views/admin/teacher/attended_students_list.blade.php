@extends('admin.layouts.master')
@section('title','Attended Students')
@section('contents')

<style>
    .asl-wrap { padding: 8px 4px 24px; }

    .asl-panel { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:18px; }
    .asl-panel-head {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:14px; border-bottom:1px solid #e4e4e4;
    }
    .asl-panel-title { font-size:1rem; font-weight:500; color:#0f172a; margin:0 0 15px 0; }

    /* Filter bar */
    .asl-filter {
        display:flex; flex-wrap:wrap; gap:12px;
        align-items:flex-end; justify-content:flex-start;
        margin-bottom:16px;
    }
    .asl-filter .fld-course { width: 400px; max-width: 100%; }
    .asl-filter .fld-paper  { width: 320px; max-width: 100%; }
    @media (max-width: 480px) {
        .asl-filter .fld-course, .asl-filter .fld-paper { width: 100%; }
    }
    .asl-filter label {
        display:block; font-size:0.78rem; font-weight:600; color:#334155; margin:0 0 6px;
    }
    .asl-filter .form-control {
        border: 1px solid #e5e7eb !important; background: #f8fafc !important;
        border-radius: 10px !important; padding: 9px 12px !important; font-size: 0.92rem;
    }
    .asl-filter .form-control:focus {
        border-color:#6366f1 !important; background:#fff !important;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.12) !important;
    }
    .asl-btn {
        display:inline-flex; align-items:center; gap:6px;
        padding:9px 16px; border-radius:10px; font-size:0.86rem;
        border:0; cursor:pointer; line-height:1; height:38px; white-space:nowrap;
    }
    .asl-btn.primary { background: linear-gradient(135deg, #bcd4f8 0%, #89b7fb 100%); color:#1a2232; }
    .asl-btn.primary:hover { box-shadow: 0 6px 14px rgba(99,102,241,0.30); }
    .asl-btn.ghost   { background:#fff; color:#475569; border:1px solid #e2e8f0; }
    .asl-btn.ghost:hover { background:#f1f5f9; color:#0f172a; }
    .asl-btn:disabled { opacity:0.55; cursor:not-allowed; }

    /* Table cells — reuse class names emitted by the controller */
    .stu { display: inline-flex; align-items: center; gap: 8px; }
    .stu .avt {
        width:28px; height:28px; border-radius:50%;
        background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
        color:#fff; font-weight:700; font-size:0.78rem;
        display:inline-flex; align-items:center; justify-content:center;
    }
    .stu .nm  { font-weight: 500; }
    .stu .sub { display:block; font-size:0.72rem; color:#94a3b8; }

    .pill-tag {
        display:inline-flex; align-items:center; gap:4px;
        padding:3px 9px; border-radius:999px;
        font-size:0.74rem; font-weight:600;
    }
    .pill-tag.green  { background: rgba(34,197,94,0.12);  color:#16a34a; }
    .pill-tag.red    { background: rgba(244,63,94,0.12);  color:#e11d48; }
    .pill-tag.amber  { background: rgba(245,158,11,0.14); color:#d97706; }

    .desc-mark-cell { font-weight: 500; color: #0f172a; }

    /* Evaluate button + modal */
    .btn-evaluate {
        display:inline-flex; align-items:center; gap:6px;
        background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 100%);
        border:1px solid #c7d2fe; color:#4338ca;
        padding:5px 14px; border-radius:999px;
        font-size:0.78rem; font-weight:500; cursor:pointer;
        transition: transform 0.12s ease, box-shadow 0.12s ease;
    }
    .btn-evaluate:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(99,102,241,0.18); }
    .btn-evaluate i { color:#4338ca; font-size:0.9rem; }

    .ev-modal-body { padding: 18px 22px; }
    .ev-progress { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .ev-progress .step { font-size:0.82rem; color:#64748b; font-weight:600; }
    .ev-progress .step strong { color:#0f172a; }
    .ev-progress .total {
        background: rgba(34,197,94,0.12); color:#16a34a;
        padding:4px 12px; border-radius:999px; font-size:0.78rem; font-weight:700;
    }
    .ev-question {
        background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px;
        padding:14px 16px; font-size:0.94rem; color:#0f172a; line-height:1.5; margin-bottom:12px;
    }
    .ev-label {
        font-size:0.78rem; font-weight:700; color:#64748b;
        text-transform:uppercase; letter-spacing:0.4px; margin:14px 0 6px;
    }
    .ev-answer {
        background:#fff; border:1px solid #e5e7eb; border-radius:10px;
        padding:12px 14px; font-size:0.92rem; color:#334155; line-height:1.5;
        white-space:pre-wrap; min-height:80px;
    }
    .ev-answer.empty { color:#94a3b8; font-style:italic; }
    .ev-mark-row { display:flex; align-items:center; gap:12px; margin-top:14px; }
    .ev-mark-row label { font-size:0.86rem; font-weight:600; color:#0f172a; margin:0; }
    .ev-mark-row input[type=number] {
        width:120px; padding:8px 12px;
        border:1px solid #e5e7eb; border-radius:10px;
        font-size:0.95rem; background:#f8fafc;
    }
    .ev-mark-row input[type=number]:focus {
        outline:none; border-color:#6366f1; background:#fff;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.12);
    }
    .ev-nav { display:flex; gap:10px; justify-content:space-between; align-items:center; }
    .ev-nav .left, .ev-nav .right { display:flex; gap:8px; }
    .btn-ev {
        padding:7px 16px; border-radius:10px; border:1px solid #c7d2fe;
        background:#fff; color:#4338ca; font-weight:600; font-size:0.85rem; cursor:pointer;
    }
    .btn-ev:hover { background:#eef2ff; }
    .btn-ev:disabled { opacity:0.5; cursor:not-allowed; }
    .btn-ev.primary { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color:#fff; border:0; }

    #datatable thead th {
        background:#f8fafc; color:#64748b;
        font-size:0.74rem; text-transform:uppercase; letter-spacing:0.4px;
    }
</style>

<div class="asl-wrap">
    <div class="asl-panel">
        <div class="asl-panel-head">
            <h3 class="asl-panel-title">
                <i class="fa fa-users me-2" style="color:#2563eb;"></i>Attended Students
            </h3>
        </div>

        {{-- ===== Filter bar ===== --}}
        <div class="asl-filter">
            <div class="fld-course">
                <label for="flt_course">Course</label>
                <select id="flt_course" class="form-control">
                    <option value="">Select course…</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->course_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fld-paper">
                <label for="flt_paper">Question Paper</label>
                <select id="flt_paper" class="form-control" disabled>
                    <option value="">Select course first…</option>
                </select>
            </div>
            <div>
                <button type="button" id="btnGet" class="asl-btn primary"><i class="fa fa-filter"></i> Get</button>
            </div>
            <div>
                <button type="button" id="btnClear" class="asl-btn ghost"><i class="fa fa-eraser"></i> Clear</button>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table id="datatable" class="table align-middle" style="width:100% !important;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Candidate ID</th>
                        <th>Mobile</th>
                        <th>Test Date</th>
                        <th class="text-end">Total Q</th>
                        <th class="text-end">Correct</th>
                        <th class="text-end">Wrong</th>
                        <th class="text-end">Skipped</th>
                        <th class="text-end">Descriptive Mark</th>
                        <th class="text-end">Score</th>
                        <th class="text-end">Evaluate</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== Evaluate Modal ===== --}}
<div class="modal fade" id="evaluateModal" tabindex="-1" aria-labelledby="evaluateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="evaluateModalLabel">
            <i class="fa fa-edit text-primary me-2"></i>
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
jQuery(function ($) {
    var currentPaperId = null;
    var dt = null;

    dt = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        scrollX: true,
        pagingType: 'simple_numbers',
        order: [[10, 'desc']],
        ajax: {
            url: "{{ route('teacher.attended-students-list-data') }}",
            data: function (d) { d.question_paper_id = currentPaperId; }
        },
        columns: [
            { data: 'DT_RowIndex',     orderable: false, searchable: false },
            { data: 'student',         orderable: false, searchable: false },
            { data: 'candidate_id',    orderable: false },
            { data: 'mobile',          orderable: false },
            { data: 'test_date',       orderable: true,  searchable: false },
            { data: 'total_questions', orderable: false, searchable: false, className: 'text-end' },
            { data: 'correct_pill',    orderable: false, searchable: false, className: 'text-end' },
            { data: 'wrong_pill',      orderable: false, searchable: false, className: 'text-end' },
            { data: 'skipped_pill',    orderable: false, searchable: false, className: 'text-end' },
            { data: 'desc_mark',       orderable: false, searchable: false, className: 'text-end' },
            { data: 'score',           orderable: true,  searchable: false, className: 'text-end' },
            { data: 'evaluate',        orderable: false, searchable: false, className: 'text-end' }
        ],
        language: { emptyTable: 'Select a course and a question paper, then click Get.' }
    });

    // Cascade: Course → Papers
    $('#flt_course').on('change', function () {
        var cid = $(this).val();
        var $pap = $('#flt_paper');
        $pap.prop('disabled', true).html('<option value="">Loading…</option>');
        if (!cid) { $pap.html('<option value="">Select course first…</option>'); return; }
        $.getJSON('/teacher/exam-results-papers/' + cid, function (res) {
            var html = '<option value="">Select paper…</option>';
            if (res && res.papers && res.papers.length) {
                res.papers.forEach(function (p) {
                    var d = p.start_date ? ' (' + p.start_date + ')' : '';
                    html += '<option value="' + p.id + '">' + p.question_paper_name + d + '</option>';
                });
            } else {
                html = '<option value="">No papers for this course</option>';
            }
            $pap.html(html).prop('disabled', false);
        }).fail(function () {
            $pap.html('<option value="">Failed to load papers</option>');
        });
    });

    $('#btnGet').on('click', function () {
        var paperId = $('#flt_paper').val();
        if (!paperId) { alert('Please select a course and a question paper.'); return; }
        currentPaperId = paperId;
        dt.ajax.reload();
    });

    $('#btnClear').on('click', function () {
        $('#flt_course').val('');
        $('#flt_paper').html('<option value="">Select course first…</option>').prop('disabled', true);
        currentPaperId = null;
        dt.ajax.reload();
    });

    /* ===== Evaluate modal — same flow as the other teacher pages ===== */
    var modal     = null;
    var questions = [];
    var current   = 0;
    var visited   = new Set();
    var studentId = null;
    var studentName = '';

    function $$(id) { return document.getElementById(id); }
    var MARK_MIN = 0, MARK_MAX = 3;

    function isCurrentMarkValid() {
        var raw = $$('ev-mark-input').value;
        var err = $$('ev-mark-error');
        if (raw === '') { err.style.display = 'none'; return true; }
        var n = parseFloat(raw);
        var ok = !isNaN(n) && n >= MARK_MIN && n <= MARK_MAX;
        err.style.display = ok ? 'none' : 'inline-block';
        return ok;
    }
    function commitCurrentMark() {
        if (!questions[current]) return;
        var v = $$('ev-mark-input').value;
        var n = parseFloat(v);
        if (v === '' || isNaN(n)) { questions[current].mark = 0; }
        else {
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
            $$('ev-content').style.display = 'none';
            $$('ev-empty').style.display = 'block';
            $$('ev-submit').style.display = 'none';
            return;
        }
        $$('ev-empty').style.display = 'none';
        $$('ev-content').style.display = 'block';
        visited.add(current);
        var q = questions[current];
        $$('ev-cur-no').textContent = (current + 1);
        $$('ev-total').textContent  = questions.length;
        $$('ev-question-text').innerHTML = q.question || '—';
        var ansEl = $$('ev-answer-text');
        if (q.question_answer && q.question_answer.trim() !== '') {
            ansEl.classList.remove('empty'); ansEl.textContent = q.question_answer;
        } else {
            ansEl.classList.add('empty'); ansEl.textContent = 'Student did not provide an answer.';
        }
        var mi = $$('ev-mark-input');
        mi.value = (q.mark === null || q.mark === undefined || q.mark === '') ? '' : q.mark;
        mi.focus();
        $$('ev-prev').disabled = (current === 0);
        $$('ev-next').disabled = (current === questions.length - 1);
        $$('ev-total-mark').textContent = totalMark();
        $$('ev-submit').style.display = (visited.size >= questions.length) ? '' : 'none';
    }
    function openEvaluate(stuId, stuName) {
        if (!currentPaperId) { alert('Please filter by a question paper first.'); return; }
        studentId = stuId; studentName = stuName || '';
        $$('ev-student-name').textContent = studentName;
        $$('ev-loading').style.display = 'block';
        $$('ev-content').style.display = 'none';
        $$('ev-empty').style.display = 'none';
        $$('ev-submit').style.display = 'none';
        visited = new Set();
        modal = new bootstrap.Modal(document.getElementById('evaluateModal'));
        modal.show();
        fetch('/teacher/descriptive-questions/' + currentPaperId + '/' + studentId, {
            headers: { 'Accept': 'application/json' }, cache: 'no-store'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            $$('ev-loading').style.display = 'none';
            questions = (data && data.questions) ? data.questions : [];
            current = 0; render();
        })
        .catch(function () {
            $$('ev-loading').style.display = 'none';
            alert('Could not load descriptive questions. Please try again.');
        });
    }

    // Delegated click — survives DataTables paging/reload.
    $(document).on('click', '#datatable .btn-evaluate', function () {
        openEvaluate(parseInt($(this).data('student-id'), 10), $(this).data('student-name') || '');
    });

    document.getElementById('ev-mark-input').addEventListener('input', function () {
        isCurrentMarkValid(); commitCurrentMark();
        $$('ev-total-mark').textContent = totalMark();
    });
    document.getElementById('ev-prev').addEventListener('click', function () {
        if (!isCurrentMarkValid()) return;
        commitCurrentMark();
        if (current > 0) { current--; render(); }
    });
    document.getElementById('ev-next').addEventListener('click', function () {
        if (!isCurrentMarkValid()) return;
        commitCurrentMark();
        if (current < questions.length - 1) { current++; render(); }
    });
    document.getElementById('ev-submit').addEventListener('click', function () {
        if (!isCurrentMarkValid()) return;
        commitCurrentMark();

        var payload = {
            question_paper_id: currentPaperId,
            student_id: studentId,
            marks: questions.map(function (q) {
                return { question_id: (q.question_id ?? q.id), mark: (q.mark === null || q.mark === '') ? 0 : q.mark };
            })
        };

        $$('ev-submit').disabled = true;
        $$('ev-submit').innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving…';

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
            $$('ev-submit').disabled = false;
            $$('ev-submit').innerHTML = '<i class="fa fa-check"></i> Submit Marks';

            if (res.ok && res.body && res.body.status) {
                // Reload the table so cells reflect the new state.
                dt.ajax.reload(null, false);
                modal.hide();
                if (typeof toastr !== 'undefined') toastr.success(res.body.message);
                else alert(res.body.message);
            } else {
                var msg = (res.body && res.body.message) ? res.body.message : 'Failed to save marks.';
                if (typeof toastr !== 'undefined') toastr.error(msg); else alert(msg);
            }
        })
        .catch(function () {
            $$('ev-submit').disabled = false;
            $$('ev-submit').innerHTML = '<i class="fa fa-check"></i> Submit Marks';
            alert('Network error — please try again.');
        });
    });
});
</script>
@endpush

@endsection
