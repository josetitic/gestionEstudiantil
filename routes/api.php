<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentByCourseController;
use App\Http\Controllers\AuthController;

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('auth/register',[AuthController::class,'create']);
Route::post('auth/login',[AuthController::class,'login']);


Route::middleware(['auth:sanctum'])->group(function (){
    Route::controller(CourseController::class)->group(function (){
        Route::get('/courses', 'index');
        Route::get('/course/{id}', 'show');
        Route::post('/course/create',  'store');
        Route::post('/course/assignseveral',  'assignseveral');
        Route::post('/course/assign',  'assign');
        Route::get('/courses/studentsbycourse/{id}', 'studentsbycourse');
        Route::get('/courses/studentsallcourses/', 'studentsallcourses');
        Route::put('/course/edit/{id}', 'update');
        Route::delete('/course/delete/{id}',  'destroy');
        Route::get('/courses/top3', 'top3');
    });

    Route::controller(StudentController::class)->group(function (){
        Route::get('/students', 'index');
        Route::get('/student/{id}', 'show');
        Route::post('/student/create',  'store');
        Route::get('/students/coursesbystudent/{id}', 'coursesbystudent');
        Route::put('/student/edit/{id}', 'update');
        Route::delete('/student/delete/{id}',  'destroy');
    });

    Route::get('auth/logout',[AuthController::class,'logout']);
});




