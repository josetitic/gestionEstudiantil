<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\Student;
use App\Models\Studentsbycourse;

use DB;

date_default_timezone_set('America/Bogota');

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::select('courses.*', DB::raw('count(studentsbycourses.id) as countstudent'))
            ->leftJoin('studentsbycourses', 'studentsbycourses.course_id', '=', 'courses.id')
            ->groupBy('courses.id')
            ->get();

        return response()->json([
            'code'=>200,
            'status'=> true,
            'data'=> $courses
        ]);
    }

    public function show($id)
    {
        $course = Course::select('courses.*',
                        DB::raw('count(studentsbycourses.id) as totalstudents'),
                        DB::raw('(SELECT GROUP_CONCAT(studentsbycourses.student_id) FROM studentsbycourses WHERE studentsbycourses.course_id = courses.id) as students')
                    )
                ->leftJoin('studentsbycourses', function ($join) {
                    $join->on('studentsbycourses.course_id', '=', 'courses.id');
                })
                ->where('courses.id', $id)
                ->groupBy('courses.id')
                ->first();

        $studentIds = explode(',',$course->students);
        $studentsForCourse = Student::whereIn('id', $studentIds)->get();
        $course->students = $studentsForCourse;

        return response()->json([
            'code'=>200,
            'status'=> true,
            'data'=> $course
        ]);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(),Course::$validatorData);

        if ($validator->fails()){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],200);
        }

        $course = new Course($request->input());
        $course->save();

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "Course created successfully"
        ],200);
    }

    public function update(Request $request,  $id)
    {

        $validator = \Validator::make($request->input(),Course::$validatorDataUpdate);

        if ($validator->fails()){
            return response()->json([
                'code'=> 200,
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],200);
        }

        $course = Course::find($id);

        if ($course == null){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The record does not exist"
            ],200);
        }

        $course->update($request->all());

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "Course updated successfully"
        ],200);

    }


    public function destroy($id)
    {
        $course = Course::find($id);

        if ($course == null){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The record does not exist"
            ],200);
        }

        $courseDel = DB::table('studentsbycourses')
        ->select(DB::raw('count(*) as students_count'))
        ->where('course_id', '=', $id)
        ->groupBy('course_id')
        ->get();

        if($courseDel[0]->students_count == 0){
            $course->delete();
            return response()->json([
                'code'=> 200,
                'status'=> true,
                'message'=> "Course deleted successfully"
            ],200);
        }else{
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The course has students"
            ],200);
        }
    }

    public function assignseveral(Request $request)
    {

        $validator = \Validator::make($request->input(),Studentsbycourse::$validatorData);

        $data = $request;

        //valid course
        $course = Course::find($data->course_id);

        if ($course == null){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The course does not exist"
            ],200);
        }

        $courseId = $data->course_id;

        //valid students
        $listStudents=$data->student_id;

        if (count($listStudents) == 0){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The students is empty"
            ],200);
        }

        $st = 0;
        for($i=0;$i<count($listStudents);$i++){
            $student = Student::find($listStudents[$i]);
            if ($student != null){
                //validate that the student is not assigned to the course
                $assignExist = DB::table('studentsbycourses')
                    ->select(DB::raw('count(*) as assign_count'))
                    ->where('course_id', '=', $courseId)
                    ->where('student_id', '=', $listStudents[$i])
                    ->groupBy('course_id')
                    ->get();


                if(count($assignExist) == 0){
                    $studentsbycourses = new Studentsbycourse();
                    $studentsbycourses->course_id = $courseId;
                    $studentsbycourses->student_id =  $listStudents[$i];
                    $studentsbycourses->save();
                    $st++;
                }
            }
        }

        if ($st > 0){
            return response()->json([
                'code'=> 200,
                'status'=> true,
                'message'=> "Assign created successfully"
            ],200);
        }else{
            return response()->json([
                'code'=> 400,
                'status'=> true,
                'message'=> "no students were assigned to the course"
            ],200);
        }
    }

    public function assign(Request $request)
    {

        $validator = \Validator::make($request->input(),Studentsbycourse::$validatorData);

        $data = $request;

        //valid course
        $course = Course::find($data->course_id);

        if ($course == null){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The course does not exist"
            ],200);
        }

        $courseId = $data->course_id;

        //valid students

        $student = $data->student_id;

        $studentf = Student::find($student);

        if ($studentf == null){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'message'=> "The student does not exist"
            ],200);
        }

        $st = 0;
        $assignExist = DB::table('studentsbycourses')
                    ->select(DB::raw('count(*) as assign_count'))
                    ->where('course_id', '=', $courseId)
                    ->where('student_id', '=', $student)
                    ->groupBy('course_id')
                    ->get();


        if(count($assignExist) == 0){
            $studentsbycourses = new Studentsbycourse();
            $studentsbycourses->course_id = $courseId;
            $studentsbycourses->student_id =  $student;
            $studentsbycourses->save();
            $st++;
        }

        if ($st > 0){
            return response()->json([
                'code'=> 200,
                'status'=> true,
                'message'=> "Assign created successfully"
            ],200);
        }else{
            return response()->json([
                'code'=> 400,
                'status'=> true,
                'message'=> "no students were assigned to the course"
            ],200);
        }
    }

    public function top3()
    {
        $topCourses = DB::table('studentsbycourses')
            ->select('course_id',DB::raw('courses.*'), DB::raw('count(student_id) as total_students'))
            ->leftJoin('courses', 'studentsbycourses.course_id', '=', 'courses.id')
            ->where('studentsbycourses.created_at', '>=', now()->subMonths(6))
            ->groupBy('studentsbycourses.course_id')
            ->orderByDesc('total_students')
            ->limit(3)
            ->get();

        $courseIds = $topCourses->pluck('course_id');
        $topCoursesDetails = Course::whereIn('id', $courseIds)->get();

        return response()->json([
            'code'=>200,
            'status'=> true,
            'data'=> $topCourses
        ],200);
    }
}
