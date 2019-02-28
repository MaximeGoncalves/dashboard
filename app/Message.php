<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $with = ['children', 'from'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function children()
    {
        return $this->hasMany(Message::class, 'to_id');
    }
}
