<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class format extends Model
{
    public function video()
    {
        return $this->belongsToMany(Video::class);
    }
}
