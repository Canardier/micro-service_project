<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Video extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'duration',
        'user_id',
        'source',
        'view',
        'enabled',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function format()
    {
        return $this->belongsToMany(Format::class, "video_format");
    }
    
    function comments()
    {
        return $this->hasMany(Comments::class);
    }
}
