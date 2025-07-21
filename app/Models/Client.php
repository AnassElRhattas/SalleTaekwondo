<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'birth_date',
        'profile_picture'
    ];

    // protected $casts = [
    //     'registration_date' => 'date'
    // ];
}