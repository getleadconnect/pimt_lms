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
 * Downloadable Excel template for the bulk Course import. The header row
 * names MUST match the keys CourseImport reads.
 */
class CourseImportTemplate implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Courses';
    }

    public function headings(): array
    {
        return [
            'course_name',           // required
            'center_id',
            'course_category_id',
            'course_type_id',
            'start_date',            // YYYY-MM-DD
            'end_date',              // YYYY-MM-DD
            'rate',
            'discount_rate',
            'ios_rate',
            'app_store_product_id',
            'subscription_type',
            'description',
            'course_details',
            'premium',               // 0 or 1
            'status',                // 1 = active, 0 = inactive
        ];
    }

    public function array(): array
    {
        return [
            [
                'Sample Course Name',
                1,
                1,
                1,
                date('Y-m-d'),
                date('Y-m-d', strtotime('+90 days')),
                1000,
                900,
                999,
                'com.example.course',
                'monthly',
                'Short course description.',
                'Full course details / HTML allowed.',
                1,
                1,
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->getFont()->setBold(true);
        $sheet->getStyle('A1:O1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDBEAFE');

        $sheet->freezePane('A2');
        return [];
    }
}
