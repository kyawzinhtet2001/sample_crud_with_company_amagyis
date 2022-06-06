<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Student;

class StudentImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row){
            Student::create(
                [
                    "student_id"=> $row[1],
                    "name"=> $row[2],
                    "father_name"=> $row[3],
                    "nrc_number"=> $row[4],
                    "phone_no"=> $row[5],
                    "email"=>$row[6],
                    "gender"=>$row[7],
                    "date_of_birth"=>$row[8],
                    "avatar"=>$row[9],
                    "address"=>$row[10],
                    "career_path"=>$row[11],
                    "created_emp"=>$row[12],
                    "updated_emp"=>$row[13],
                    "deleted_at"=>$row[14],
                    "created_at"=>$row[15],
                    "updated_at"=>$row[16]
                ]
            );
        }
    }
}
