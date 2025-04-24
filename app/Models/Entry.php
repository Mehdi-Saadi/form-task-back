<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'photos',
    ];

    protected $casts = [
        'date' => 'date',
        'photos' => 'array', // cast JSON to array automatically
    ];
}
