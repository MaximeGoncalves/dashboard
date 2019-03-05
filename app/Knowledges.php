<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Knowledges extends Model
{
    public function category (){
        return $this->belongsTo(KnowledgesCategory::class, 'Knowledges_Categories_id');
    }

    public function user (){
        return $this->belongsTo(User::class);
    }
}
