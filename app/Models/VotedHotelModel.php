<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotedHotelModel extends Model
{
    use HasFactory;
    protected $table = "voted_hotel";
    protected $fillable = [
        'hotel_title',
        'hotel_website',
        'hotel_description',
        'hotel_thumbnail',
        'created_at',
        'updated_at'
    ];
    
}
