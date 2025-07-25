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
        'group',
        'birth_date',
        'profile_picture',
        'Birth_contract',
        'payer_abon'
    ];

    // protected $casts = [
    //     'registration_date' => 'date'
    // ];
}