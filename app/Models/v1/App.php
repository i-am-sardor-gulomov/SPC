<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'url',
        'IP',
        'port',
        'grant_type',
        'client_id',
        'client_secret',
        'icon'
    ];

    protected $hidden = [
        'IP',
        'port',
        'grant_type',
        'client_id',
        'client_secret',
    ];
}
