<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeHotelModel extends Model
{
    use HasFactory;
    protected $table = "home_hotels";
    protected $fillable = [
        'country_name',
        'title',
        'description',
        'u_tube_link',
        'created_at',
        'updated_at',
    ];
}
