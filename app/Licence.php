<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    public function society(){
        return $this->belongsTo(Society::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsTo(LicencesCategories::class);
    }
}
