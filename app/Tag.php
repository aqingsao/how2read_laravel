<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{  
  use SoftDeletes;
  public function questions()
  {
    return $this->belongsToMany('App\Question', 'question_tags');
  }
}
