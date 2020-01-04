<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $primaryKey = "id_wishlist";
    
    protected $fillable = [
        'title'
    ];

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'id_wishlist');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}