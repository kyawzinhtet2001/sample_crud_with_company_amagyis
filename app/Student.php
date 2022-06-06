<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    Use SoftDeletes;
    protected $table="students";
    protected $guarded=[];
    public function skills(){
        return $this->belongsToMany(Skill::class,"student_skills","student_id","skill_id","student_id");
    }
}
