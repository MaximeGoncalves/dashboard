<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    private $content;

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

}
