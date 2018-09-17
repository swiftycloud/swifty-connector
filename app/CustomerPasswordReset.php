<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerPasswordReset extends Model
{
    protected $fillable = [
        'email', 'token'
    ];

    protected $hidden = [
        'token'
    ];
}
