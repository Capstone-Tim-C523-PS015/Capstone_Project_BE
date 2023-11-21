<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function login(Request $request) {
        if(auth()->guard('api')->check()){
            return response()->json(['message' => 'Kamu sudah login'], 400);
        }

        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Data tidak valid'], 400);
        }

        $creadential = $request->only(['email', 'password']);
        $token = auth()->guard('api')->attempt($creadential);

        if(!$token){
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
        ]);
    }

    function register(Request $request) {
        if(auth()->guard('api')->check()){
            return response()->json(['message' => 'Kamu sudah login'], 400);
        }

        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return response()->json(['message' => 'Data tidak valid'], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'message' => 'Register berhasil',
                'user' => $user,
            ], 201);
        }
        catch(QueryException $e){
            $message = 'Query error';
            if($e->errorInfo[1] == 1062){
                $message = 'Email sudah digunakan';
            }
            return response()->json([
                'message' => $message,
            ],400);
        }
    }
}
