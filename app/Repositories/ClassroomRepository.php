<?php
namespace App\Repositories;

use \Exception;
use App\Interfaces\ClassroomRepositoryInterface;
use App\Model\Classroom;

/**
 * This is ClassroomRepository
 * @author kyaw zin htet
 * @created_at 6/6/2022
 *
 */
class ClassroomRepository implements ClassroomRepositoryInterface
{
    public function getAll(){
       $colleciton= Classroom::all();
       if($colleciton->isEmpty()){
           throw new Exception();
       }
       return $colleciton;
    }
    public function getOne(int $id){
        try{
            return Classroom::findOrfail($id);
        }catch(Exception $e){
            throw $e;
        }
    }
    public function insert(array $id){
        $model=Classroom::create($id);
        if($model){
            return 1;
        }
        return 0;

    }
    public function delete(int $id){
        $s=Classroom::whereId($id)->delete();
        if($s<=0){
            throw new Exception();
        }
    }
    public function update(array $array,int $id){
        $s=Classroom::whereId($id)->update($array);
        if($s<=0){
            throw new Exception();
        }
    }
}

?>
