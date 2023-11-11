<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studentsbycourse extends Model
{
    use HasFactory;

    protected $table = "studentsbycourses";

    protected $primaryKey = 'id';

    protected $fillable = [
        'course_id',
        'student_id'
    ];

    public static $validatorData = [
        'course_id' => 'required',
        'student_id' => 'required'
    ];
}
