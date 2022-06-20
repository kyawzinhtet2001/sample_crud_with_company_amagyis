<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id');
            $table->unique('teacher_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('nrc_number',40)->nullable()->default(null);
            $table->string('phone_no',30)->nullable()->default(null);
            $table->string('email');
            $table->tinyInteger('gender',false,true);//1:Male,2:Female
            $table->date('date_of_birth')->nullable();
            $table->string('avatar')->nullable()->default(null);
            $table->string('address',500)->nullable();
            $table->tinyInteger('career_path',false,true)->nullable()->default(1);//1:Fornt End,2:Back End
            $table->integer('created_emp');
            $table->integer('updated_emp');
            $table->softDeletes();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
