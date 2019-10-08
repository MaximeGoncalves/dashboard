<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    public function tickets(){
    return $this->hasMany(Ticket::class, 'technician_id');
}
    public function user(){
        return $this->belongsTo(User::class);
    }
}
