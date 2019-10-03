<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function society(){
        return $this->belongsTo(Society::class);
    }
    public function source(){
        return $this->belongsTo(Source::class);
    }
    public function messages()
    {
        return $this->morphMany(Message::class, 'commentable');
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function actions()
    {
        return $this->hasMany(Action::class);
    }
    public function notes(){
        return $this->hasMany(Ticket::class);
    }
    public function type(){
        return $this->belongsTo(Type::class);
    }

}
