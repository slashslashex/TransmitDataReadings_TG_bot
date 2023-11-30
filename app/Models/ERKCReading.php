<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ERKCReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'cold water previous readings',
        'cold water new readings',
        'hot water previous readings',
        'hot water new readings',
        'hot water difference',
        'cold water difference'
    ];
}
