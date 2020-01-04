<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = "id_product";

    protected $fillable = [
        'title'
    ];

    public function wishlist()
    {
        return $this->belongsTo('App\Models\Wishlist', 'id_wishlist');
    }
}
