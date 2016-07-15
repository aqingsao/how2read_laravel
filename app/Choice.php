<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
  protected $fillable = array('is_correct', 'name_ipa', 'name_alias', 'name_cn', 'is_correct', 'audio_url');
  public function question()
  {
    return $this->belongsTo(Question::class);
  }
}