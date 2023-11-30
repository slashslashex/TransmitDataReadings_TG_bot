<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DVECElectricityReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'previous readings',
        'new readings',
        'difference',
    ];


}
