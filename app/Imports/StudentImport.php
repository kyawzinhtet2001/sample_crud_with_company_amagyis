<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Student;

class StudentImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // dd($collection);
        foreach($collection as $row){
            Student::create(
                [
                    "student_id"=> $row["student_id"],
                    "name"=> $row["name"],
                    "father_name"=> $row["father_name"],
                    "nrc_number"=> $row["nrc_number"],
                    "phone_no"=> $row["phone_number"],
                    "email"=>$row["email"],
                    "gender"=>$row["gender"]==='male'? "male":'female',
                    "date_of_birth"=>$row["date_of_birth"],
                    "avatar"=>$row["avatar"],
                    "address"=>$row["address"],
                    "career_path"=>$row["career_path"],
                    "created_emp"=>$row["created_emp"],
                    "updated_emp"=>$row["updated_emp"],
                    "created_at"=>now(),
                    "updated_at"=>now()
                ]
            );
        }
    }
    public function headingRow(): int
    {
        return 1;
    }
}
