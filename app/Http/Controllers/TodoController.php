<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    function all(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $todos = Todo::latest()->get();
            return response()->json(['message' => $todos]);
        }
    }

    function single(Request $request, $id)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $todo = Todo::find($id);
            if (!$todo) {
                return response()->json(['message' => 'data tidak ditemukan'], 404);
            }

            return response()->json([
                'message' => "data ditemukan",
                'data' => $todo,
            ]);
        }
    }

    function store(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'deadline' => 'required',
                'status' => 'required',
                'userId' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'data tidak valid', 'request' => $request->all()], 400);
            }

            $todo = Todo::create($request->only(['title', 'description', 'deadline', 'status', 'userId']));

            return response()->json([
                'message' => "data berhasil ditambahkan",
                'data' => $todo,
            ]);
        }
    }

    function update(Request $request, $id)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'deadline' => 'required',
                'status' => 'required',
                'userId' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'data tidak valid', 'request' => $request->all()], 400);
            }

            $todo = Todo::find($id);
            if (!$todo) {
                return response()->json(['message' => 'data tidak ditemukan'], 404);
            }
            $todo->update($request->only(['title', 'description', 'deadline', 'status', 'userId']));

            return response()->json([
                'message' => "data berhasil diperbarui",
                'data' => $todo,
            ]);
        }
    }

    function delete(Request $request, $id)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $todo = Todo::find($id);
            if (!$todo) {
                return response()->json(['message' => 'data tidak ditemukan'], 404);
            }
            $todo->delete();

            return response()->json([
                'message' => "data berhasil dihapus",
            ]);
        }
    }
}
