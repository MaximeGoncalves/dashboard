<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $with = ['parent', 'from'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

//    public function children()
//    {
//        return $this->hasMany(Message::class, 'to_id');
//    }
    public function parent()
    {
        return $this->belongsTo(Message::class, 'to_id');
    }
}
