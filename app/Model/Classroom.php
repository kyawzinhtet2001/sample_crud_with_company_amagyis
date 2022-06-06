<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table="class";

    protected $fillable=["name","limit"];
}
