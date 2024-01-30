<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeInfoModel extends Model
{
    use HasFactory;

    protected $table = "home_info";
    protected $fillable = [
        'type',
        'details',
        'created_at',
        'updated_at',
    ];
}
