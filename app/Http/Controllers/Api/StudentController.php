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
/**
 * Student crud controller.
 * @author kyaw zin htet
 * @create 6/1/2022
 */
class StudentController extends Controller
{
    /**
     * List all or find something.
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function index()
    {
        $search = request()->get("search");
        $type = request()->get("type");
        if ($search != null) {
            if (!empty($type)) {
                if ($type == 1) {
                    $students = DB::table('students')->where("id", $search)->paginate(10);
                }
                elseif ($type == 2) {
                    $students = DB::table('students')->where("name", "like", $search . "%")->paginate(10);
                }
                elseif ($type == 3) {
                    $students = DB::table('students')->where("email", "like", $search . "%")->paginate(10);
                }
                elseif ($type == 4) {
                    $students = DB::table('students')->where("career_path", $search)->paginate(10);
                }
            }
            else {
                try {
                    (int)$search;
                    $students = DB::table('students')->where("student_id","like","%".$search."%")->orWhere("career_path", $search)->paginate(10);
                // $students = DB::table('students')->where("id", $search)->orWhere("career_path", $search)->paginate(10);
                }
                catch (Exception $ex) {
                    $students = DB::table('students')->where("email", "like", "%".$search . "%")->orWhere("name", "like", "%" .$search . "%")->paginate(10);
                }
            }
            // dd($students);
        }
        else {
            $students = DB::table("students")->paginate(10);
        }
        return response()->json(["status" => "OK", "data" => $students]);
    }
    /**
     * List detail
     * @param int $id
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function detail(int $id)
    {
        $students = DB::table('students')->leftJoin("student_skills", "student_skills.student_id", "=", "students.student_id")->where('students.id', $id)->select("students.*", "student_skills.*", "students.id as id")->get();
        $arr = [];
        foreach ($students as $student) {
            array_push($arr, $student->skill_id);
        }
        // dd($students->get(0));
        $students->get(0)->skills = $arr;
        if ($students->isNotEmpty()) {
            return response()->json(["status" => "OK", "data" => $students->first()]);
        }
        return response()->json(["status" => "NG", "message" => "No Data Found"]);

    }
    /**
     * Store a student to database
     * @param StudentRequest $request
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function store(StudentRequest $request)
    {
        try {
            $student_id = max(DB::table('students')->max('student_id'), 10000);
            $student_id++;
            $data = $request->validated();

            $data['created_emp'] = $data['emp_id'];
            $data['updated_emp'] = $data['emp_id'];
            unset($data['emp_id']);
            $data['student_id'] = $student_id;
            $skills = null;
            if (isset($data['avatar'])) {
                $avater = $data['avatar'];
                $avater->storeAs('/public', $avater->getClientOriginalName());
                $data['avatar'] = $avater->getClientOriginalName();
                unset($avater);
            }

            if (isset($data['skills'])) {
                $skills = $data['skills'];
            }

            unset($data['skills']);

            $result = DB::transaction(function () use ($data, $skills, $student_id) {
                DB::table("students")->insert([
                    $data
                ]);
                $arr = [];
                if (!empty($skills)) {
                    foreach ($skills as $i) {
                        array_push($arr, [
                            "student_id" => $student_id,
                            "skill_id" => $i,
                            "created_emp" => $data['created_emp'],
                            "updated_emp" => $data["updated_emp"]
                        ]);
                    }
                }
                DB::table("student_skills")->insert($arr);

                $result = true;
                return $result;
            });

            if ($result) {
                return response()->json(['status' => "OK", "message" => "Created Student"]);
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
     * @param StudentRequest $request
     * @author kyaw zin htet
     * @create 6/1/2022
     */
    public function update(int $id, StudentRequest $request)
    {
        try {
            $data = $request->validated();
            unset($data['student_id']);
            if (isset($data["skills"])) {
                $skills = $data['skills'];
                unset($data['skills']);
            }
            $data['created_emp'] = $data['emp_id'];
            $data['updated_emp'] = $data['emp_id'];
            unset($data['emp_id']);
            $old = DB::table('students')->where('id', $id)->first();
            if(!$old){
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
            $result = DB::transaction(function () use ($id, $data, $old, $skills) {
                DB::table('students')->where('id', $id)->update($data);
                DB::table('student_skills')->where('student_id', $old->student_id)->delete();
                $arr = [];
                if (!empty($skills)) {
                    foreach ($skills as $i) {

                        array_push($arr, [
                            "student_id" => $old->student_id,
                            "skill_id" => $i,
                            "created_emp" => $data['created_emp'],
                            "updated_emp" => $data["updated_emp"]
                        ]);

                    }
                    DB::table('student_skills')->insert($arr);
                }


                return true;
            });
            if ($result) {
                return response()->json(['status' => "OK", "message" => "Updated Student"]);
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
            $s = DB::table('students')->where('id', $id)->first();
            if (!$s) {
                throw new Exception("Nothing Found");
            }

            if (is_file(storage_path('app/public/') . $s->avatar))
                Storage::delete('public/' . $s->avatar);
            $s = DB::table('students')->delete($id);
            DB::commit();
            return response()->json(["status" => "OK", "message" => "Student is Deleted"]);
        }
        catch (Exception $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());
            return response()->json(["status" => "NG", 'message' => "Already deleted or pls check logs"]);
        } // dd()
    // dd(is_file(storage_path('app/public/') . $s->avatar));
    // dd(Storage::delete('public/' . $s->avatar));
    }
}
