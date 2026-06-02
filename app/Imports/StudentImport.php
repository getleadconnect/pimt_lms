<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Bulk import students. For every row we create:
 *   1. students          – the student profile
 *   2. users             – login record (default password "12345")
 *   3. subscriptions     – student_id, course_id, status  (if course_id given)
 *
 * Each row is wrapped in its own transaction so a single bad row doesn't
 * abort previously imported rows.
 */
class StudentImport implements OnEachRow, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    protected $addedBy;
    protected $centerId;

    public $imported = 0;
    public $skipped  = 0;
    public $errors   = [];

    public function __construct($addedBy = null, $centerId = null)
    {
        $this->addedBy  = $addedBy;
        $this->centerId = $centerId;
    }

    public function onRow(Row $row)
    {
        $r = $row->toArray();
        $rowNo = $row->getIndex();

        if (empty($r['student_name']) || empty($r['mobile'])) {
            $this->skipped++;
            $this->errors[] = "Row {$rowNo}: student_name and mobile are required.";
            return;
        }

        // Don't create duplicate logins on the same mobile.
        if (Student::where('mobile', $r['mobile'])->exists()) {
            $this->skipped++;
            $this->errors[] = "Row {$rowNo}: mobile '{$r['mobile']}' already exists.";
            return;
        }

        DB::beginTransaction();
        try {
            $student = Student::create([
                'center_id'     => $this->intOrNull($r['center_id'] ?? null) ?: $this->centerId,
                'candidate_id'  => $r['candidate_id'] ?? null,
                'student_name'  => trim((string) $r['student_name']),
                'date_of_birth' => $this->parseDate($r['date_of_birth'] ?? null),
                'district_id'   => $this->intOrNull($r['district_id'] ?? null),
                'place'         => $r['place']  ?? null,
                'email'         => $r['email']  ?? null,
                'mobile'        => trim((string) $r['mobile']),
                'status'        => $this->intOrNull($r['status'] ?? 1) ?? 1,
                'added_by'      => $this->addedBy,
            ]);

            User::create([
                'student_id'           => $student->id,
                'student_candidate_id' => $r['candidate_id'] ?? null,
                'email'                => $r['email'] ?? null,
                'mobile'               => trim((string) $r['mobile']),
                'password'             => Hash::make($r['password'] ?? '12345'),
                'status'               => $this->intOrNull($r['status'] ?? 1) ?? 1,
            ]);

            // Only create a subscription row if course_id is provided.
            if (!empty($r['course_id'])) {
                Subscription::create([
                    'student_id'  => $student->id,
                    'course_id'   => $this->intOrNull($r['course_id']),
                    'rate'        => $this->floatOrNull($r['rate'] ?? null),
                    'net_amount'  => $this->floatOrNull($r['net_amount'] ?? ($r['rate'] ?? null)),
                    'start_date'  => $this->parseDate($r['subscription_start_date'] ?? null),
                    'end_date'    => $this->parseDate($r['subscription_end_date']   ?? null),
                    'status'      => $this->intOrNull($r['subscription_status'] ?? ($r['status'] ?? 1)) ?? 1,
                    'added_by'    => $this->addedBy,
                ]);
            }

            DB::commit();
            $this->imported++;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->skipped++;
            $this->errors[] = "Row {$rowNo}: " . $e->getMessage();
        }
    }

    public function rules(): array
    {
        return [
            '*.student_name' => 'required|string|max:100',
            '*.mobile'       => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.student_name.required' => 'student_name is required on every row.',
            '*.mobile.required'       => 'mobile is required on every row.',
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

    private function parseDate($v)
    {
        if ($v === null || $v === '') return null;
        if (is_numeric($v)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            } catch (\Exception $e) { /* fall through */ }
        }
        try {
            return Carbon::parse($v)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
