<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_skill', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id');
            $table->unsignedBigInteger('skill_id');
            $table->foreign("teacher_id")->references("teacher_id")->on("teachers")->onDelete('cascade');
            $table->foreign("skill_id")->references("id")->on("skills")->onDelete('cascade');

            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_skill');
    }
}
