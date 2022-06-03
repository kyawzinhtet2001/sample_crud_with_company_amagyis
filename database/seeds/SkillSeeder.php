<?php

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr=[
            'c++',
            'php',
            'java',
            'react',
            'andorid',
            'laravel'
        ];

        foreach($arr as $i){
            DB::table('skills')->insert([
            'name'=>ucfirst($i),
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
            'created_emp'=>1,
            'updated_emp'=>1,
            ]);
        }


    }
}
