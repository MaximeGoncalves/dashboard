<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KnowledgesCategory extends Model
{
    public $timestamps = false;

    public function knowledges (){
        return $this->hasMany(Knowledges::class, 'Knowledges_Categories_id');
    }
}
