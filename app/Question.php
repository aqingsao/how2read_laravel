<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function choices()
    {
        return $this->hasMany('App\Choice');
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
