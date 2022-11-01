<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;
    protected $guarded = []; 
    
    public function category() {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function relatedPost() {
        return Blog::where('is_active', 1)->get();
    }

}
