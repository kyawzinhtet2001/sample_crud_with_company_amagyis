<?php

namespace App\Exports;

use App\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::all();
    }
    public function headings():array{
        return [
            "id",
            "student_id",
            "name",
            "father_name",
            "nrc_number",
            "phone_number",
            "email",
            "gender",
            "date_of_birth",
            "avatar",
            "address",
            "career_path",
            "created_emp",
            "updated_emp",
            "deleted_at",
            "created_at",
            "updated_at"
        ];
    }
}
