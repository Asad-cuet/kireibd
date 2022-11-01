<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KireiYoutube extends Model
{
    use HasFactory;
    protected $guarded = [];
public function kireiYoutebeCategory()
    {
        return $this->hasOne(KireiYoutubeCategory::class, 'id', 'category_id');
    }
}
