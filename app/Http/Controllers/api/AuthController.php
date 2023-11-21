<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function login(Request $request) {
        if(auth()->guard('api')->check()){
            return response()->json(['message' => 'Kamu sudah login.'], 400);
        }

        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Data tidak valid.'], 400);
        }

        $creadential = $request->only(['email', 'password']);
        $token = auth()->guard('api')->attempt($creadential);

        if(!$token){
            return response()->json(['message' => 'Username atau password salah.'], 401);
        }

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
        ]);
    }

    function register(Request $request) {
        
    }
}
