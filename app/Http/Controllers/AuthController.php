<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function create(Request $request){
        $validator = \Validator::make($request->input(),User::$validatorData);

        if ($validator->fails()){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "User created successfully $user",
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ],200);
    }

    public function login(Request $request){
        $validator = \Validator::make($request->input(),User::$validatorLogin);

        if ($validator->fails()){
            return response()->json([
                'code'=> 400,
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],200);
        }

        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'code'=> 401,
                'status'=> false,
                'errors'=> ['Unauthorizer']
            ],200);
        }

        $user = User::where('email',$request->email)->first();

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "User logged successfully",
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ],200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'code'=> 200,
            'status'=> true,
            'message'=> "User logged out successfully"
        ],200);
    }
}
