<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
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
            $activities = Activity::where(['userId' => $userId])->latest()->get();
            return response()->json([
                'message' => 'data ditemukan',
                'total' => $activities->count(),
                'activities' => $activities,
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

            $activity = Activity::find($id);
            if (!$activity) {
                return response()->json(['message' => 'data tidak ditemukan'], 404);
            }

            return response()->json([
                'message' => "data ditemukan",
                'activity' => $activity,
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
                'category' => 'required',
                'deadline' => 'required',
                'isNotificate' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'data tidak valid', 'request' => $request->all()], 400);
            }
            // $isNotificate = filter_var($request->isNotificate, FILTER_VALIDATE_BOOL);
            $isNotificate = $request->isNotificate == "true" || $request->isNotificate === true || $request->isNotificate == 1 ;

            $userId = auth()->payload()['sub'];
            $activity = Activity::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'deadline' => $request->deadline,
                'isNotificate' => $isNotificate,
                'userId' => $userId,
            ]);

            return response()->json([
                'message' => "data berhasil ditambahkan",
                'activity' => $activity,
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
                'category' => 'required',
                'deadline' => 'required',
                'isNotificate' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'data tidak valid', 'request' => $request->all()], 400);
            }

            $activity = Activity::find($id);
            if (!$activity) {
                return response()->json(['message' => 'data tidak ditemukan'], 404);
            }

            $isNotificate = $request->isNotificate == "true" || $request->isNotificate === true || $request->isNotificate == 1 ;
            $activity->update($request->only([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'deadline' => $request->deadline,
                'isNotificate' => $isNotificate,
            ]));

            return response()->json([
                'message' => "data berhasil diperbarui",
                'activity' => $activity,
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

            $activity = Activity::find($id);
            if (!$activity) {
                return response()->json(['message' => 'data tidak ditemukan'], 404);
            }
            $activity->delete();

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
            $activities = Activity::where(['userId' => $userId])->whereDate('deadline', Carbon::today())->get();

            return response()->json([
                'message' => $activities->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $activities->count(),
                'activities' => $activities,
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
            $activities = Activity::where(['userId' => $userId])->whereDate('deadline', Carbon::today()->subDay())->get();

            return response()->json([
                'message' => $activities->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $activities->count(),
                'activities' => $activities,
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
            $activities = Activity::where(['userId' => $userId])->whereDate('deadline', Carbon::today()->addDay())->get();

            return response()->json([
                'message' => $activities->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $activities->count(),
                'activities' => $activities,
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
            $activities = Activity::where(['userId' => $userId])->whereBetween('deadline', [$from, $to])->get();

            return response()->json([
                'message' => $activities->count() > 0 ? "data ditemukan" : "data kosong",
                'total' => $activities->count(),
                'activities' => $activities,
            ]);
        }
    }
}