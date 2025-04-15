<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Mail\Registration;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;

class JwtAuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'statusCode' => 400,
                'message' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);
        
        return response()->json([
            'statusCode' => 201,
            'user' => $user,
            'token' => $token
        ], 201);

    }

    public function login(Request $request){
        $cred = $request->only('email', 'password');

        try{
            if(! $token = JWTAuth::attempt($cred)){
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Invalid credentials'
                ], 401);
            }
        }catch(JWTException $e){
            return response()->json([
                'statusCode' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }

        $user = JWTAuth::user();

        return response()->json([
            'statusCode' => 200,
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function profile(){
        $user = JWTAuth::user();
        return response()->json([
            'statusCode' => 200,
            'user' => $user
        ], 200);
    }

    public function logout(){

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'statusCode' => 200,
            'message' => 'Successfully logged out'
        ], 200);
    }
}
