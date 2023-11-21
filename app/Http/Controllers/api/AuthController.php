<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function login(Request $request) {
        if(auth()->guard('api')->check()){
            return response()->json(['message' => 'kamu sudah login'], 400);
        }

        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'data tidak valid'], 400);
        }

        $creadential = $request->only(['email', 'password']);
        $token = auth()->guard('api')->attempt($creadential);

        if(!$token){
            return response()->json(['message' => 'username atau password salah'], 401);
        }

        return response()->json([
            'message' => 'login berhasil',
            'token' => $token,
        ]);
    }

    function register(Request $request) {
        if(auth()->guard('api')->check()){
            return response()->json(['message' => 'kamu sudah login'], 400);
        }

        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return response()->json(['message' => 'data tidak valid'], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'message' => 'register berhasil',
                'user' => $user,
            ], 201);
        }
        catch(QueryException $e){
            $message = 'Query error';
            if($e->errorInfo[1] == 1062){
                $message = 'email sudah digunakan';
            }
            return response()->json([
                'message' => $message,
            ],400);
        }
    }

    function logout(Request $request) {
        if(!$request->bearerToken()){
            return response()->json([
                'message' => 'token diperlukan',
            ],401);
        }

        if(!auth()->guard('api')->check()){
            return response()->json(['message' => 'token tidak valid'],400);
        }

        Auth::guard('api')->logout();
        return response()->json(['message' => 'logout berhasil']);
    }
}
