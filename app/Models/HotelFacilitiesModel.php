<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelFacilitiesModel extends Model
{
    use HasFactory;

    protected $table = "hotel_facilities";
    protected $fillable = [
        'title',
        'image',
        'sort_order ',
        'type',
        'created_at',
        'updated_at',
    ];
}
