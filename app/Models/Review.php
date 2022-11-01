<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  public function user(){
    return $this->belongsTo(User::class,'user_id','id');
  }

  public function product(){
    return $this->belongsTo(Product::class);
  }

  public function reviewReplay()
  {
    return $this->belongsTo(ReviewReplay::class);
  }
}
