<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\ClassroomRepositoryInterface;
use \Exception;
use App\Http\Requests\ClassroomRequest;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClassroomController extends Controller
{
    private ClassroomRepositoryInterface $classroomRepository;

    public function __construct(ClassroomRepositoryInterface $classroom){
        $this->classroomRepository=$classroom;
    }
    public function index(){
        try{
            return response()->json(["status"=>"OK","data"=>$this->classroomRepository->getAll()]);
        }catch(Exception $e){
            return response()->json(["status"=>"NG","message"=>"No Data Found"]);
        }
    }
    public function store(ClassroomRequest $request){
        try{
            $validated_data=$request->validated();
            $this->classroomRepository->insert($validated_data);
            return response()->json(["status"=>"OK","message"=>"Class is inserted"],201);
        }catch(Exception $e){
            return response()->json(["status"=>"NG","message"=>"No Data Found"]);
        }
    }
    public function update(int $id,ClassroomRequest $request){
        try{
            $validated_data=$request->validated();
            $this->classroomRepository->update($validated_data,$id);
            return response()->json(["status"=>"OK","message"=>"Class is updated"]);
        }catch(Exception $e){
            return response()->json(["status"=>"NG","message"=>"Already Deleted or not exists"]);
        }
    }
    public function destroy(int $id){
        try{
        $this->classroomRepository->delete($id);
        return response()->json(["status"=>"OK","message"=>"Class is deleted"]);
        }catch(Exception $e){
            return response()->json(["status"=>"NG","message"=>"Already Deleted or not exists"]);
        }
    }
    public function detail(int $id):JsonResponse{
        try{
            return response()->json($this->classroomRepository->getOne($id));
        }catch(Exception $e){
            return response()->json(["status"=>"NG","message"=>"No Data Found"]);
        }
    }
}
