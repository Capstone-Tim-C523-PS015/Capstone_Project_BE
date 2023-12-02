<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function index(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token diperlukan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $id = auth()->payload()['sub'];
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'user tidak ditemukan'], 404);
            }
            if (!$user->description) {
                $user->description = '';
            };

            return response()->json([
                'message' => 'user ditemukan',
                'user' => $user,
            ]);
        }
    }

    function update(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token diperlukan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $id = auth()->payload()['sub'];
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'user tidak ditemukan'], 404);
            }

            try {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                    'description' => 'required',
                    'profileImage' => 'mimes:jpg,png',
                ]);
                if ($validator->fails()) {
                    return response()->json(['message' => 'data tidak valid'], 400);
                }

                $pfImage = env('APP_URL') . '/profile/default.png';
                if ($request->file('profileImage')) {
                    $image = $request->file('profileImage');
                    if ($user->profileImage != $pfImage) {
                        $path = explode("/", $user->profileImage);
                        $path = array_slice($path, 3);
                        $path = public_path().'/'.implode("/", $path);

                        if(file_exists($path)){
                            unlink($path);
                        }
                    }
                    $imgName = $id . "-" . time() . "." . $image->getClientOriginalExtension();
                    $image->move(public_path('profile'), $imgName);
                    $pfImage = env('APP_URL') . '/profile/' . $imgName;
                    $pfImage;
                }
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'description' => $request->description,
                    'profileImage' => $pfImage,
                ]);

                return response()->json([
                    'message' => "data berhasil diperbarui",
                    'user' => $user,
                ]);
            } catch (QueryException $e) {
                $message = $e->errorInfo[2];
                if ($e->errorInfo[1] == 1062) {
                    $message = 'email sudah digunakan';
                }
                return response()->json([
                    'message' => $message,
                ], 400);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 400);
            }
        }
    }

    function delete(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token diperlukan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $id = auth()->payload()['sub'];
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'user tidak ditemukan'], 404);
            }

            # Remove profile image
            $pfImage = env('APP_URL') . '/profile/default.png';
            if ($user->profileImage != $pfImage) {
                $path = explode("/", $user->profileImage);
                $path = array_slice($path, 3);
                $path = public_path().'/'.implode("/", $path);

                if(file_exists($path)){
                    unlink($path);
                }
            }

            auth()->logout();
            $user->delete();

            return response()->json([
                'message' => "user berhasil dihapus",
            ]);
        }
    }
}
