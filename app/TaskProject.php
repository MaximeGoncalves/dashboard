<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskProject extends Model
{
    public function tasks(){
        return $this->hasMany(Task::class, 'project_id');
    }
}
