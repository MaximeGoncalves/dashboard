<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

    protected $with = ['ticket', 'from'];

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }

    public function from(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
