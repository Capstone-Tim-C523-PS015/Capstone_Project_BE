<?php

namespace App\Console\Commands;

use App\Models\Todo;
use Error;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class UpdateTodo extends Command
{
    protected $signature = 'jobs:todo';
    protected $description = 'Update todo status every minute';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try{
            $kemarin = Todo::where('deadline', '<', Carbon::now())->where(['status'=>'dikerjakan'])->orWhere(['status'=>'menunggu'])->get(['id','status']);
            $kemarin->each(function($todo){
                $todo->update(['status' => 'telat']);
            });
            
            $sekarang = Todo::whereDate('deadline', Carbon::now())->where(['status'=>'menunggu'])->get();
            $sekarang->each(function($todo){
                $todo->update(['status' => 'dikerjakan']);
            });
    
            $kemarin = Todo::where('deadline', '>', Carbon::now())->where(['status'=>'telat'])->get(['id','status']);
            $kemarin->each(function($todo){
                $todo->update(['status' => 'menunggu']);
            });
            
            $pesan = date("Y-m-d H:i:s").": Data berhasil diperbarui\n";
            $logsFile = fopen('todo.log','a');
            fwrite($logsFile, $pesan);
            fclose($logsFile);
        }catch(Error $e){
            $pesan = $e->getMessage()."\n";
            $logsFile = fopen('todo.log','a');
            fwrite($logsFile, $pesan);
            fclose($logsFile);
        }
    }
}
