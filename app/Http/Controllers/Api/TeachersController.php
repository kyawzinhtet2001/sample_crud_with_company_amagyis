<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StudentRequest;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Teacher;
use App\Http\Requests\TeacherRequest;
/**
 * Student crud controller.
 * @author kyaw zin htet
 * @create 6/1/2022
 */
class TeachersController extends Controller
{
    /**
     * List all or find something.
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function index()
    {
        try {
            $search = request()->get("search");
            $type = request()->get("type");
            if ($search != null) {
                if (!empty($type)) {
                    if ($type == 1) {
                        $students = Teacher::where("teacher_id", $search)->paginate(10);
                    }
                    elseif ($type == 2) {
                        $students = Teacher::where("name", "like", $search . "%")->paginate(10);
                    }
                    elseif ($type == 3) {
                        $students = Teacher::where("email", "like", $search . "%")->paginate(10);
                    }
                    elseif ($type == 4) {
                        $students = Teacher::where("career_path", $search)->paginate(10);
                    }

                }
                else {
                    try {
                        (int)$search;
                        $students = Teacher::where("teacher_id", "like", "%" . $search . "%")->orWhere("career_path", $search)->paginate(10);
                    // $students = DB::table('students')->where("id", $search)->orWhere("career_path", $search)->paginate(10);
                    }
                    catch (Exception $ex) {
                        $students = Teacher::where("email", "like", "%" . $search . "%")->orWhere("name", "like", "%" . $search . "%")->paginate(10);
                    }
                }
            // dd($students);
            }
            else {
                $students = Teacher::paginate(10);
            }
            if ($students->isEmpty()) {
                throw new Exception();
            }
            return response()->json(["status" => "OK", "data" => $students]);
        }
        catch (Exception $e) {
            return response()->json(["status" => "NG", "message" => "No Data Found"]);
        }
    }
    /**
     * List detail
     * @param int $id
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function detail(int $id)
    {

        $student = Teacher::where("id", $id)->with(["skills"=>function($query){
            $query->select("skills.name");
        }])->first();

        if ($student) {
            return response()->json(["status" => "OK", "data" => $student]);
        }
        return response()->json(["status" => "NG", "message" => "No Data Found"]);

    }
    /**
     * Store a student to database
     * @param TeacherRequest $request
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function store(TeacherRequest $request)
    {
        try {
            $teacher_id = max(DB::table('teachers')->max('teacher_id'), 10000);
            $teacher_id++;
            $data = $request->validated();

            $data['created_emp'] = $data['emp_id'];
            $data['updated_emp'] = $data['emp_id'];
            unset($data['emp_id']);
            $data['teacher_id'] = $teacher_id;
            $skills = null;
            if (isset($data['avatar'])) {
                /** @var \Illuminate\Http\UploadedFile */
                $avater = $data['avatar'];
                $avater->storeAs('/public', $avater->getClientOriginalName());
                $data['avatar'] = $avater->getClientOriginalName();
                unset($avater);
            }

            if (isset($data['skills'])) {
                $skills = $data['skills'];
            }

            unset($data['skills']);

            $result = DB::transaction(function () use ($data, $skills, $teacher_id) {
                Teacher::insert([
                    $data
                ]);
                $arr = [];
                if (!empty($skills)) {
                    foreach ($skills as $i) {
                        array_push($arr, [
                            "teacher_id" => $teacher_id,
                            "skill_id" => $i,

                        ]);
                    }
                }
                DB::table("teacher_skill")->insert($arr);

                $result = true;
                return $result;
            });

            if ($result) {
                return response()->json(['status' => "OK", "message" => "Created Teacher"]);
            }
            throw new Exception();
        }
        catch (Exception $e) {
            return response()->json(['status' => "NG", "message" => "Duplicate Data"]);
        }
    }
    /**
     * Update Student.
     * @param int $id
     * @param  TeacherRequest $request
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function update(int $id, TeacherRequest $request)
    {
        try {
            $data = $request->validated();
            unset($data['student_id']);
            if (isset($data["skills"])) {
                $skills = $data['skills'];
                unset($data['skills']);
            }
            $old = Teacher::where('id', $id)->first();
            if (!$old) {
                throw new Exception();
            }
            if (isset($data['avatar'])) {
                if ($old->avatar != $data['avatar']->getClientOriginalName()) {
                    if (is_file(storage_path('app/public/') . $old->avatar))
                        Storage::delete('public/' . $old->avatar);
                    $data['avatar']->storeAs('/public', $data['avatar']->getClientOriginalName());
                }
                $data['avatar'] = $data['avatar']->getClientOriginalName();

            }

            $data['updated_emp'] = $data['emp_id'];
            unset($data['emp_id']);
            $result = DB::transaction(function () use ($id, $data, $old, $skills) {
                Teacher::where('id', $id)->update($data);
                DB::table('teacher_skill')->where('teacher_id', $old->teacher_id)->delete();
                $arr = [];
                if (!empty($skills)) {
                    foreach ($skills as $i) {

                        array_push($arr, [
                            "teacher_id" => $old->teacher_id,
                            "skill_id" => $i,
                        ]);

                    }
                    DB::table('teacher_skill')->insert($arr);
                }
                return true;
            });
            if ($result) {
                return response()->json(['status' => "OK", "message" => "Updated Teacher check to list to view more"]);
            }
            throw new Exception("Not Found");
        }
        catch (Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(['status' => "NG", "message" => "Not Found"]);
        }

    }
    /**
     * Delete a student.
     * @param int $id
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $s = Teacher::where('id', $id)->first();
            if (!$s) {
                throw new Exception("Nothing Found");
            }

            $s->delete();
            DB::commit();
            if (is_file(storage_path('app/public/') . $s->avatar))
                Storage::delete('public/' . $s->avatar);
            // Teacher::destroy($id);
            return response()->json(["status" => "OK", "message" => "Teacher is Deleted"]);
        }
        catch (Exception $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());
            return response()->json(["status" => "NG", 'message' => "Already deleted or pls check logs"]);
        }
    }
}
