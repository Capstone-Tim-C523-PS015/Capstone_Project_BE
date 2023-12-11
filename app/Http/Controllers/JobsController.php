<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Support\Carbon;

class JobsController extends Controller
{
    function updateStatus() {
        $kemarin = Todo::whereDate('deadline', '<=', Carbon::now()->subDay())->where(['status'=>'dikerjakan'])->get(['id','status']);
        $kemarin->each(function($todo){
            $todo->update(['status' => 'telat']);
        });
        
        $sekarang = Todo::whereDate('deadline', Carbon::now())->where(['status'=>'menunggu'])->get();
        $sekarang->each(function($todo){
            $todo->update(['status' => 'dikerjakan']);
        });
        
        return response()->json([
            'message' => 'data berhasil diperbarui',
        ]);
    }
}