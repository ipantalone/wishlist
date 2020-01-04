<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $primaryKey = "id_user";

    protected $fillable = [
        'name', 'surname', 'email',
    ];

    protected $hidden = [
        'password', 'token'
    ];

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist', 'id_user');
    }
}
