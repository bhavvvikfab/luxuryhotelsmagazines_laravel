<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatWeDoModel extends Model
{
    use HasFactory;
    protected $table = "what_we_do";
    protected $fillable = [
        'type',
        'details',
        'created_at',
        'updated_at',
    ];
}
