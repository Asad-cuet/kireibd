<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyBlog extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function beautyBlogCategory()
    {
        return $this->hasOne(BeautyBlogCategory::class, 'id', 'category_id');
    }

}
