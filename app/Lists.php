<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    protected $with = ['tasks', 'tasks.comments', 'tasks.subtasks','tasks.attachments'];
    public function tasks (){
        return $this->hasMany(Task::class, 'list_id');
    }
    public function board (){
        return $this->belongsTo(Board::class, 'board_id');
    }
}
