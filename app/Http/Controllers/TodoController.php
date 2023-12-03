<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
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

            $userId = auth()->payload()['sub'];
            $todos = Todo::where(['userId' => $userId])->latest()->get();
            return response()->json([
                'message' => $todos->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $todos->count(),
                'todos' => $todos,
            ]);
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
                'todo' => $todo,
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
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'data tidak valid', 'request' => $request->all()], 400);
            }

            $userId = auth()->payload()['sub'];
            $todo = Todo::create([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'status' => $request->status,
                'userId' => $userId,
            ]);

            return response()->json([
                'message' => "data berhasil ditambahkan",
                'todo' => $todo,
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
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'data tidak valid', 'request' => $request->all()], 400);
            }

            $todo = Todo::find($id);
            if (!$todo) {
                return response()->json(['message' => 'data tidak ditemukan'], 404);
            }
            $todo->update($request->only(['title', 'description', 'deadline', 'status']));

            return response()->json([
                'message' => "data berhasil diperbarui",
                'todo' => $todo,
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

    function now(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $userId = auth()->payload()['sub'];
            $todos = Todo::where(['userId' => $userId])->whereDate('deadline', Carbon::today())->get();

            return response()->json([
                'message' => $todos->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $todos->count(),
                'todos' => $todos,
            ]);
        }
    }

    function yesterday(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $userId = auth()->payload()['sub'];
            $todos = Todo::where(['userId' => $userId])->whereDate('deadline', Carbon::today()->subDay())->get();

            return response()->json([
                'message' => $todos->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $todos->count(),
                'todos' => $todos,
            ]);
        }
    }

    function tomorrow(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $userId = auth()->payload()['sub'];
            $todos = Todo::where(['userId' => $userId])->whereDate('deadline', Carbon::today()->addDay())->get();

            return response()->json([
                'message' => $todos->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $todos->count(),
                'todos' => $todos,
            ]);
        }
    }

    function span(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $validator = Validator::make($request->all(), [
                'from' => 'required',
                'to' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'data tidak valid', 'request' => $request->all()], 400);
            }

            $from = Carbon::parse($request->from);
            $to = Carbon::parse($request->to)->addDay();

            $userId = auth()->payload()['sub'];
            $todos = Todo::where(['userId' => $userId])->whereBetween('deadline', [$from, $to])->get();

            return response()->json([
                'message' => $todos->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $todos->count(),
                'todos' => $todos,
            ]);
        }
    }
    
    function history(Request $request) {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'token dibutuhkan'], 401);
        } else {
            if (!auth()->check()) {
                return response()->json(['message' => 'token tidak valid'], 401);
            }

            $userId = auth()->payload()['sub'];
            $todos = Todo::where(['userId' => $userId])->whereIn('status', ['revisi','selesai'])->get();
            $revisi = [];
            $selesai = [];
            foreach ($todos as $todo) {
                if($todo->status == 'revisi'){
                    $revisi[] = $todo;
                }else{
                    $selesai[] = $todo;
                }
            }

            return response()->json([
                'message' => $todos->count() > 0 ? "data ditemukan" : "data kosong",
                'todos' => [
                    'revisi' => [
                        'total' => count($revisi),
                        'data' => $revisi,
                    ],
                    'selesai' => [
                        'total' => count($selesai),
                        'data' => $selesai,
                    ],
                ],
            ]);
        }
    }
}
