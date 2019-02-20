<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    public function users(){
        return $this->hasMany(User::class);
    }
}
