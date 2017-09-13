<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "usuarios";
    public $timestamps = false;
    protected $fillable = [
        'user','password'
    ];
}
