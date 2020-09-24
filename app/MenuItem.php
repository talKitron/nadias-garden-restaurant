<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    // protected $fillable = ['name', 'description', 'price', 'category_id'];

    public function category() {
        return $this->belongsTo('App\Category');
    }
    
    public function images() {
        return $this->hasMany('App\MenuItemImage');
    }
}
