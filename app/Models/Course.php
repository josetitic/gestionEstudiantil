<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = "courses";

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'schedule',
        'startdate',
        'enddate'
    ];

    public static $validatorData = [
        'name' => 'required|string|min:1|max:35|unique:courses,name',
        'schedule' => 'required|string|min:3|max:35',
        'startdate' => 'required|date',
        'enddate' => 'required|date'
    ];

    public static $validatorDataUpdate = [
        'name' => 'required|string|min:1|max:35',
        'schedule' => 'required|string|min:3|max:35',
        'startdate' => 'required|date',
        'enddate' => 'required|date'
    ];
}
