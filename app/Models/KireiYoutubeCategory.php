<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KireiYoutubeCategory extends Model
{
    use HasFactory;
    protected $guarded = [];


        public function kireiYoutube()
    {
        return $this->belongsTo(KireiYoutube::class);
    }
    public function allYoutube()
    {
        return $this->hasMany(KireiYoutube::class,'category_id');
    }
}
