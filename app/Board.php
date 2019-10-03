<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
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
    public function lists(){
        return $this->hasMany(Lists::class);
    }
    public function tasks(){
        return $this->hasMany(Task::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
