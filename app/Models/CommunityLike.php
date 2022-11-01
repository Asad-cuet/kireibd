<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityLike extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'post_id');
    }
}
