<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyBlogCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function beautyBlog()
    {
        return $this->hasMany(BeautyBlog::class,'category_id');
    }
}
