<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    //
    protected $guarded = [];

    public function brands()
    {
        return $this->hasMany(Brand::class, 'category_id');
    }
}
