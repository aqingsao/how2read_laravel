<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
  use SoftDeletes;
  protected $fillable = array('name', 'description', 'source_type', 'source_url', 'remark');

  public function choices()
  {
    return $this->hasMany('App\Choice');
  }
  public function tags()
  {
    return $this->belongsToMany('App\Tag', 'question_tags');
  }

  public function issue()
  {
    return $this->belongsTo('App\Issue');
  }
  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
