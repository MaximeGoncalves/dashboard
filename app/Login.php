<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class login extends Model
{
    protected $guarded = [];

    public function society(){
        return $this->belongsTo(Society::class);
    }
}
