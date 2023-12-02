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
                'activities' => $activities,
            ]);
        }
    }
}