<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSpecialOfferModel extends Model
{
    use HasFactory;

    protected $table = "special_offer";
    protected $fillable = [
        'hotel_id',
        'offer_title',
        'contact_no',
        'from_date',
        'to_date',
        'created_at',
        'updated_at',
    ];
}
