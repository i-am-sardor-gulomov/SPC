<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use HasFactory;

    protected $fillable = [
        'front_login',
        'front_password',
        'back_login',
        'back_password',
        'user_id',
        'app_id'
    ];

    protected $hidden = [
        'front_password',
        'back_password'
    ];
}
