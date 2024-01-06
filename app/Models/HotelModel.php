<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelModel extends Model
{
    use HasFactory;

    protected $table = "hotels";
    protected $fillable = [
        'user_id',
        'hotel_title',
        'address',
        'hotel_images',
        'rooms_and_suites',
        'other_facilities',
        'youtube_link',
        'website',
        'contact_no',
        'created_at',
        'updated_at',
    ];
}
