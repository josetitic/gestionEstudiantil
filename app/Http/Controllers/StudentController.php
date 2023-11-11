<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Course;

use DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::select('students.*', DB::raw('count(studentsbycourses.id) as totalcourses'))
        ->leftJoin('studentsbycourses', 'studentsbycourses.student_id', '=', 'students.id')
        ->groupBy('students.id')
        ->orderBy('students.lastname', 'asc')
        ->orderBy('students.name', 'asc')
        ->paginate(10);

        return response()->json([
            'code'=>200,
            'status'=> true,
            'data'=> $students
        ]);
    }

    public function show($id)
    {
        $student = Student::select('students.*',
                        DB::raw('count(studentsbycourses.id) as totalcourses'),
                        DB::raw('(SELECT GROUP_CONCAT(studentsbycourses.course_id) FROM studentsbycourses WHERE studentsbycourses.student_id = students.id) as courses')
                    )
                ->leftJoin('studentsbycourses', function ($join) {
                    $join->on('studentsbycourses.student_id', '=', 'students.id');
                })
                ->where('students.id', $id)
                ->groupBy('students.id')
                ->first();

        $coursesIds = explode(',',$student->courses);
        $coursesForStudent = Course::whereIn('id', $coursesIds)->get();
        $student->courses = $coursesForStudent;

        return response()->json([
            'code'=>200,
            'status'=> true,
            'data'=> $student
        ]);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(),Student::$validatorData);

        if ($validator->fails()){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],200);
        }

        $student = new Student($request->input());
        $student->save();

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "Student created successfully"
        ],200);
    }

    public function update(Request $request,  $id)
    {

        $validator = \Validator::make($request->input(),Student::$validatorDataUpdate);

        if ($validator->fails()){
            return response()->json([
                'code'=> 200,
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],200);
        }

        $student = Student::find($id);

        if ($student == null){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The record does not exist"
            ],200);
        }

        $student->update($request->all());

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "Student updated successfully"
        ],200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if ($student == null){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The record does not exist"
            ],200);
        }

        $courseDel = DB::table('studentsbycourses')
        ->select(DB::raw('count(*) as students_count'))
        ->where('student_id', '=', $id)
        ->groupBy('student_id')
        ->get();

        if(count($courseDel) == 0){
            $student->delete();
        }else{
            $courseDel = DB::table('studentsbycourses')->where('student_id', $id)->delete();
        }

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "Student deleted successfully"
        ],200);

    }
}
