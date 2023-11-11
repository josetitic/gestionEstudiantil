<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = "students";

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'lastname',
        'age',
        'email'
    ];

    public static $validatorData = [
        'name' => 'required|string|min:1|max:35',
        'lastname' => 'required|string|min:3|max:35',
        'age' => 'required|integer',
        'email' => 'required|email|max:35|unique:students,email'
    ];

    public static $validatorDataUpdate = [
        'name' => 'required|string|min:1|max:35',
        'lastname' => 'required|string|min:3|max:35',
        'age' => 'required|integer',
        'email' => 'required|email|max:35'
    ];
}
