<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Notifications;
class Task extends Model
{
    use SoftDeletes;

    protected $with = ['board'];


    public function getMembersAttribute($value)
    {
        $members = json_decode($value);
        $memberTab = [];

        if(is_array($members) || is_object($members)){
            foreach ($members as $member){
                $memberTab[] = $member;
            }
        }
        return $memberTab;

    }

    public function list()
    {
        return $this->belongsTo(Lists::class);
    }
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function project()
    {
        return $this->belongsTo(TaskProject::class, 'project_id');
    }

    public function comments()
    {
        return $this->morphMany(Message::class, 'commentable');
    }
    public function subtasks (){
        return $this->hasMany(Subtask::class);

    }
}
