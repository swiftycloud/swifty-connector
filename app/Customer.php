<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'email', 'password', 'name', 'hash', 'confirmed'
    ];

    protected $hidden = [
        'password', 'hash',
    ];
}