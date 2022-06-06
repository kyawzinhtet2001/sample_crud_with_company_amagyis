<?php
    namespace App\Interfaces;
    use App\Student;

    interface StudentRespositoryInterface{
        function getAll();
        function getOne(int $id);
        function insert(array $array);
        function delete(int $id);
        function update(int $id,array $array);
    }
?>
