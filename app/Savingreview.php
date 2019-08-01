<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Savingreview extends Model
{
    //
    //Each saving review belongs to a user
    public function user(){
        return $this->belongsTo(User::class);
    }
}
