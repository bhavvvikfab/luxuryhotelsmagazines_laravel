<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelCreateProfileModel extends Model
{
    use HasFactory;

    protected $table = "hotel_create_profile";
    protected $fillable = [
        'type',
        'details',
        'created_at',
        'updated_at',
    ];
}
