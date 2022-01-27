<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'user',
        'body',
    ];
}
