<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Excel template for bulk Student import. The header row names MUST match
 * the keys StudentImport reads.
 */
class StudentImportTemplate implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Students';
    }

    public function headings(): array
    {
        return [
            // Required
            'student_name',
            'mobile',
            // Student profile (optional)
            'email',
            'date_of_birth',          // YYYY-MM-DD
            'district_id',
            'place',
            'center_id',
            'candidate_id',
            'password',               // optional — default "12345" if blank
            'status',                 // 1 = active, 0 = inactive (applies to student + user)
            // Subscription (optional — only created if course_id is filled)
            'course_id',
            'rate',
            'net_amount',
            'subscription_start_date',
            'subscription_end_date',
            'subscription_status',    // 1 = active, 0 = inactive (defaults to status)
        ];
    }

    public function array(): array
    {
        return [
            [
                'John Doe',            // student_name (required)
                '9999999999',          // mobile        (required, must be unique)
                'john@example.com',    // email
                '2000-05-12',          // date_of_birth
                1,                     // district_id
                'Bangalore',           // place
                1,                     // center_id
                '',                    // candidate_id  (blank = no candidate id)
                '',                    // password      (blank = "12345")
                1,                     // status        (1 = active)
                1,                     // course_id     (blank = don't create subscription)
                1000,                  // rate
                900,                   // net_amount
                '',                    // subscription_start_date
                '',                    // subscription_end_date
                1,                     // subscription_status
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);
        $sheet->getStyle('A1:P1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDBEAFE');

        $sheet->freezePane('A2');
        return [];
    }
}
