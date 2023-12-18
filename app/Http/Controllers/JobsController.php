<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Support\Carbon;

class JobsController extends Controller
{
    function updateStatus() {
        $kemarin = Todo::where('deadline', '<', Carbon::now())->where(['status'=>'dikerjakan'])->orWhere(['status'=>'menunggu'])->get(['id','status']);
        $kemarin->each(function($todo){
            $todo->update(['status' => 'telat']);
        });
        
        $sekarang = Todo::whereDate('deadline', '=', Carbon::today())->where(['status'=>'menunggu'])->get(['id','status']);
        $sekarang->each(function($todo){
            $todo->update(['status' => 'dikerjakan']);
        });

        $kemarin = Todo::where('deadline', '>', Carbon::now())->where(['status'=>'telat'])->get(['id','status']);
        $kemarin->each(function($todo){
            $todo->update(['status' => 'menunggu']);
        });
        
        return response()->json([
            'message' => 'data berhasil diperbarui',
            'todo' => Todo::find(27),
            'data' => Carbon::today(),
        ]);
    }
}