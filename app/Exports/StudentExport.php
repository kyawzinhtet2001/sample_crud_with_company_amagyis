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
        $s=Student::with("skills")->get();
        // dd($s);
        $i=0;
        foreach($s as $r){
                $j=0;
                $name='';
            foreach($r["skills"] as $skill){
                if($j==sizeof($r["skills"])-1){
                    $name.=$skill['name'];
                }else{
                    $name.=$skill['name'].",";
                }
                if($j!=0){
                    unset($s[$i]["skills"][$j]);
                }
                $j++;
            }
            $s[$i]["skills"]=$name;
            $i++;
        }
        //  dd($s);
        return $s;
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
            "updated_at",
            "student.skills"
        ];
    }
}
