<?php

namespace App\Models;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    public function todo()
    {
        return $this->hasMany(Todo::class);
    }
}
