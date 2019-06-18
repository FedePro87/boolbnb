<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    protected $fillable = [
        'type'
    ];

    function apartments(){
        return $this->belongsToMany(Apartment::class);
      }
}
