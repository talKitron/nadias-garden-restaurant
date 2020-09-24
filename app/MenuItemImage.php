<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItemImage extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    // protected $fillable = ['image', 'menu_item_id', 'user_id'];
    
    public function menuItem() {
        return $this->belongsTo('App\MenuItem');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
