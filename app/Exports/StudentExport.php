<?php

namespace App\Exports;

use App\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentExport implements FromCollection,WithHeadings,ShouldAutoSize,WithMapping,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $s=Student::with(["skills"=> function($query){
            $query->select('skills.name');
        }])->get();
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
            "created_at",
            "updated_at",
            "student.skills"
        ];
    }

    public function map($row):array{
        return [
            $row->id,
            $row->student_id,
            $row->name,
            $row->father_name,
            $row->nrc_number,
            $row->phone_no,
            $row->email,
            $row->gender===1 ? "male":"female",
            $row->date_of_birth ,
            $row->avatar,
            $row->address,
            $row->career_path===1 ? "FrontEnd" : "BackEnd",
            $row->created_emp,
            $row->updated_emp,
            Date::dateTimeToExcel($row->created_at),
            Date::dateTimeToExcel($row->updated_at),
            $row->skills? $row->skills:""
        ];
    }
    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_DATE_DATETIME,
            'P' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
