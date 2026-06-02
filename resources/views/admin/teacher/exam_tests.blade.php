@extends('admin.layouts.master')
@section('title','Exam Tests')
@section('contents')

<style>
    .et-wrap { padding: 8px 4px 24px; }

    .et-panel {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 16px; padding: 20px;
    }
    .et-panel-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px;
        border-bottom:1px solid #e4e4e4;
    }
    .et-panel-title { font-size: 1.02rem; font-weight: 500; color: #0f172a; margin: 0 0 15px 0; }

    /* Filter bar — left-aligned, fixed widths */
    .et-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
        justify-content: flex-start;
        margin-bottom: 16px;
    }
    .et-filter .fld-course { width: 400px; max-width: 100%; }
    .et-filter .fld-date   { width: 150px; }
    @media (max-width: 480px) {
        .et-filter .fld-course,
        .et-filter .fld-date { width: 100%; }
    }
    .et-filter label {
        display: block;
        font-size: 0.78rem; font-weight: 600; color: #334155;
        margin: 0 0 6px;
    }
    .et-filter .form-control {
        border: 1px solid #e5e7eb !important;
        background: #f8fafc !important;
        border-radius: 10px !important;
        padding: 9px 12px !important;
        font-size: 0.92rem;
    }
    .et-filter .form-control:focus {
        border-color: #6366f1 !important;
        background: #fff !important;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.12) !important;
    }
    .et-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px; border-radius: 10px;
        font-size: 0.86rem; 
        border: 0; cursor: pointer;
        line-height: 1;
        height: 38px;
        white-space: nowrap;
    }
    .et-btn.primary {
            background: linear-gradient(135deg, #bcd4f8 0%, #89b7fb 100%);
    color: #1a2232;
    }
    .et-btn.primary:hover { box-shadow: 0 6px 14px rgba(99,102,241,0.30); }
    .et-btn.ghost {
        background: #fff; color: #475569;
        border: 1px solid #e2e8f0;
    }
    .et-btn.ghost:hover { background: #f1f5f9; color: #0f172a; }

    /* Table tweaks */
    #datatable thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .btn-soft {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 100%);
        border: 1px solid #c7d2fe;
        color: #4338ca;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.78rem; 
        text-decoration: none;
    }
    .btn-soft:hover { color: #312e81; }

    /* Status pills */
    .pill-tag {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 999px;
        font-size: 0.74rem; font-weight: 600;
    }
    .pill-tag.green { background: rgba(34,197,94,0.12);  color: #16a34a; }
    .pill-tag.red   { background: rgba(244,63,94,0.12);  color: #e11d48; }
    .pill-tag.amber { background: rgba(245,158,11,0.14); color: #d97706; }
</style>

<div class="et-wrap">
    <div class="et-panel">
        <div class="et-panel-head">
            <h3 class="et-panel-title">
                <i class="fa fa-file-alt me-2" style="color:#7c3aed;"></i>Exam - Question Papers List
            </h3>
        </div>

        {{-- ===== Filter bar ===== --}}
        <div class="et-filter">
            <div class="fld-course">
                <label for="flt_course">Course</label>
                <select id="flt_course" class="form-control">
                    <option value="">All courses</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->course_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fld-date">
                <label for="flt_date_from">From Date</label>
                <input type="date" id="flt_date_from" class="form-control">
            </div>
            <div class="fld-date">
                <label for="flt_date_to">To Date</label>
                <input type="date" id="flt_date_to" class="form-control">
            </div>
            <div>
                <button type="button" id="btnGet" class="et-btn primary"><i class="fa fa-filter"></i> Get</button>
            </div>
            <div>
                <button type="button" id="btnClear" class="et-btn ghost"><i class="fa fa-eraser"></i> Clear</button>
            </div>
        </div>


        <div class="table-responsive">
            <table id="datatable" class="table align-middle" style="width:100% !important;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question Paper</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Attended</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        scrollX: true,
        pagingType: 'simple_numbers',
        order: [[3, 'desc']],
        ajax: {
            url: "{{ route('teacher.exam-tests-data') }}",
            data: function (d) {
                d.course_id = $('#flt_course').val();
                d.date_from = $('#flt_date_from').val();
                d.date_to   = $('#flt_date_to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name',        name: 'qp.question_paper_name' },
            { data: 'course_name', name: 'c.course_name' },
            { data: 'start_date',  name: 'qp.start_date' },
            { data: 'time_range',  orderable: false, searchable: false },
            { data: 'status',      orderable: false, searchable: false },
            { data: 'attended',    orderable: false, searchable: false },
            { data: 'action',      orderable: false, searchable: false, className: 'text-end' }
        ],
        language: {
            emptyTable: 'No mock tests match the current filters.'
        }
    });

    // Get → reload with current filter values (validates the date range)
    $('#btnGet').on('click', function () {
        var from = $('#flt_date_from').val();
        var to   = $('#flt_date_to').val();
        if (from && to && from > to) {
            alert('"From Date" cannot be after "To Date".');
            return;
        }
        table.ajax.reload();
    });

    // Clear → reset every filter control and reload
    $('#btnClear').on('click', function () {
        $('#flt_course').val('');
        $('#flt_date_from').val('');
        $('#flt_date_to').val('');
        table.ajax.reload();
    });

    // Keep To >= From by adjusting the min/max attributes live
    $('#flt_date_from').on('change', function () {
        $('#flt_date_to').attr('min', $(this).val() || '');
    });
    $('#flt_date_to').on('change', function () {
        $('#flt_date_from').attr('max', $(this).val() || '');
    });

    // Submit on Enter from any filter
    $('#flt_course, #flt_date_from, #flt_date_to').on('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); $('#btnGet').trigger('click'); }
    });
});
</script>
@endpush

@endsection
