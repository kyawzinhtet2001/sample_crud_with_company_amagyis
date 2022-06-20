<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    use SoftDeletes;
    protected $hidden = ['pivot'];
    public function skills(){
        return $this->belongsToMany(Skill::class,'teacher_skill','teacher_id',"skill_id",'teacher_id');
    }
}
