<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribersModel extends Model
{
    use HasFactory;
    protected $table = "subscribers";
    protected $fillable = [
        'name',
        'email',
        // 'interest',
        // 'about_us',
        'created_at',
        'updated_at',
    ];
}
