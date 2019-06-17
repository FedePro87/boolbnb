<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
    'title',
    'content'
    ];


    function apartment(){
        return $this->belongsTo(Apartment::class);
      }
}
