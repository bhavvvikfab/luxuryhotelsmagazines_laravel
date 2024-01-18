<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamModel extends Model
{
    use HasFactory;
    protected $table = "team";
    protected $fillable = [
        'name',
        'position',
        'image',
        'status',
        'created_at',
        'updated_at'
    ];
}



