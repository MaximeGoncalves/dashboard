<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    public function users(){
        return $this->hasMany(User::class);
    }

    public function tickets (){
        return $this->hasMany(Ticket::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }
}
