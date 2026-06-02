<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Carbon\Carbon;

/**
 * Bulk import courses from an Excel sheet.
 *
 * Image / file columns (course_wide_icon, course_square_icon, video_file) are
 * intentionally excluded — those are handled via the regular Add/Edit course
 * forms. added_by is set from the authenticated admin.
 */
class CourseImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    use Importable;

    protected $addedBy;

    public function __construct($addedBy = null)
    {
        $this->addedBy = $addedBy;
    }

    public function model(array $row)
    {
        // Skip rows missing the only truly mandatory field.
        if (empty($row['course_name'])) {
            return null;
        }

        return new Course([
            'center_id'            => $this->intOrNull($row['center_id']           ?? null),
            'course_name'          => trim((string) $row['course_name']),
            'course_category_id'   => $this->intOrNull($row['course_category_id']  ?? null),
            'course_type_id'       => $this->intOrNull($row['course_type_id']      ?? null),
            'start_date'           => $this->parseDate($row['start_date']          ?? null),
            'end_date'             => $this->parseDate($row['end_date']            ?? null),
            'rate'                 => $this->floatOrNull($row['rate']              ?? null),
            'discount_rate'        => $this->floatOrNull($row['discount_rate']     ?? null),
            'ios_rate'             => $this->floatOrNull($row['ios_rate']          ?? null),
            'app_store_product_id' => $row['app_store_product_id'] ?? null,
            'subscription_type'    => $row['subscription_type']    ?? null,
            'description'          => $row['description']          ?? null,
            'course_details'       => $row['course_details']       ?? null,
            'premium'              => $this->intOrNull($row['premium']             ?? 0),
            'status'               => $this->intOrNull($row['status']              ?? 1),
            'added_by'             => $this->addedBy,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.course_name' => 'required|string|max:100',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.course_name.required' => 'course_name is required on every row.',
        ];
    }

    private function intOrNull($v)
    {
        if ($v === null || $v === '') return null;
        return (int) $v;
    }

    private function floatOrNull($v)
    {
        if ($v === null || $v === '') return null;
        return (float) $v;
    }

    /**
     * Accepts: Excel serial dates, Y-m-d, d/m/Y, d-m-Y, etc.
     */
    private function parseDate($v)
    {
        if ($v === null || $v === '') return null;

        // Excel stores dates as serial numbers when the cell is formatted as date.
        if (is_numeric($v)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)
                    ->format('Y-m-d');
            } catch (\Exception $e) { /* fall through */ }
        }

        try {
            return Carbon::parse($v)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
