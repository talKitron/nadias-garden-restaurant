<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function menuItems(){
        return $this->hasMany('App\MenuItem');//'App\MenuItem'
    }
}
