<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function postHashtag()
    {
        return $this->hasMany(PostHashtag::class,'post_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
    public function comment()
    {
        return $this->hasMany(CommunityComment::class);
    }
    public function like()
    {
        return $this->hasMany(CommunityLike::class);
    }
}
