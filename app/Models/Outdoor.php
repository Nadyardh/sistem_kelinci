<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outdoor extends Model
{
    protected $table = 'outdoors';

    protected $fillable = [
        'suhu',
        'kelembapan',
        'status_cuaca',
    ];

    protected $casts = [
        'suhu'       => 'float',
        'kelembapan' => 'float',
    ];
}
