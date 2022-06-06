<?php
namespace App\Interfaces;
use App\Model\Classroom;

/**
 * This is ClassroomRepositoryInterface
 * @author kyaw zin htet
 * @created_at 6/6/2022
 *
 */
interface ClassroomRepositoryInterface
{
    function getAll();
    function getOne(int $id);
    function insert(array $id);
    function delete(int $model);
    function update(array $array,int $id);
}


?>
