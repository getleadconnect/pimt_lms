<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Exports the Exam Results table (filtered by question_paper_id) as
 * CSV/XLSX. Mirrors the columns shown on the Exam Results page.
 */
class ExamResultsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $paperId;
    protected $paperName;

    public function __construct($paperId, $paperName = null)
    {
        $this->paperId   = $paperId;
        $this->paperName = $paperName;
    }

    public function title(): string
    {
        return 'Exam Results';
    }

    public function collection()
    {
        return DB::table('test_results as tr')
            ->leftJoin('students as s', 's.id', '=', 'tr.student_id')
            ->where('tr.question_paper_id', $this->paperId)
            ->whereNotNull('tr.descriptive_mark')
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
                'tr.score',
                'tr.descriptive_mark',
                'tr.created_at',
                's.student_name',
                's.candidate_id',
                's.email',
                's.mobile',
                // Standard ranking against all evaluated students on this paper.
                DB::raw('(SELECT COUNT(*) + 1
                          FROM test_results tr2
                          WHERE tr2.question_paper_id = tr.question_paper_id
                            AND tr2.descriptive_mark IS NOT NULL
                            AND tr2.score > tr.score) as rank_position'),
            ]);
    }

    public function headings(): array
    {
        return [
            'Rank',
            'Student Name',
            'Candidate ID',
            'Email',
            'Mobile',
            'Test Date',
            'Total Questions',
            'Correct',
            'Wrong',
            'Skipped',
            'Descriptive Mark',
            'Score',
        ];
    }

    public function map($r): array
    {
        $date = $r->test_date ?: $r->created_at;
        return [
            (int) $r->rank_position,
            $r->student_name,
            $r->candidate_id,
            $r->email,
            $r->mobile,
            $date ? \Carbon\Carbon::parse($date)->format('d M Y') : '',
            (int) $r->total_questions,
            (int) $r->correct,
            (int) $r->wrong,
            (int) $r->skipped,
            $r->descriptive_mark,
            $r->score,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDBEAFE');

        $sheet->freezePane('A2');
        return [];
    }
}
