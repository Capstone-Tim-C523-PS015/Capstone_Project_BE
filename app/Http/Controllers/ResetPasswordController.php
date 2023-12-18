<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    function email(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'data tidak valid'], 400);
        }

        $email = $request->email;
        $user = User::where(['email' => $email])->get();
        if ($user->count() <= 0) {
            return response()->json(['message' => 'user tidak ditemukan'], 400);
        }
        $cekToken = Token::where(['email' => $email])->get();
        if ($cekToken->count() > 0) {
            return response()->json(['message' => 'email verifikasi sudah dikirim, harap cek spam jika email tidak ada'], 400);
        }

        $token = bin2hex(random_bytes(32));
        try {
            Mail::send('email', ['data' => 'hello world', 'token' => $token], function ($message) use ($email) {
                $message->subject('Reset Password - PlanPlan');
                $message->to($email);
            });
            Token::create([
                'token' => $token,
                'email' => $email,
            ]);
            return response()->json(['message' => 'email berhasil dikirim, cek spam apabila email tidak tersedia', 'token' => $token]);
        } catch (Error $e) {
            return response()->json(['message' => 'email gagal dikirim', 'error' => $e->getTrace()], 400);
        }
    }

    function verifyToken(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'data tidak valid'], 400);
        }

        $token = $request->token;
        $cekToken = Token::where(['token' => $token])->get();
        if ($cekToken->count() == 1) {
            return response()->json(['message' => 'token valid']);
        } else {
            return response()->json(['message' => 'token tidak valid'], 400);
        }
    }

    function updatePassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'new_password' => 'required|confirmed|min:3',
            'new_password_confirmation' => 'required',
            'token' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'data tidak valid', 'data' => $request->all()], 400);
        }

        $token = $request->token;
        $cekToken = Token::where(['token' => $token])->get();
        if ($cekToken->count() < 1) {
            return response()->json(['message' => 'token tidak valid'], 400);
        } else {
            $cekToken = $cekToken->first();
            $user = User::where(['email' => $cekToken->email])->get()->first();
            if ($user->count() < 1) {
                return response()->json(['message' => 'user tidak ditemukan'], 404);
            }

            $user->update([
                'password' => bcrypt($request->new_password),
            ]);

            $cekToken->delete();
            return response()->json(['message' => 'password berhasil diperbarui']);
        }
    }
}
