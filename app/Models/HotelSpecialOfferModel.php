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
        'news_id',
        'type',
       'offer_title',
        'contact_no',
        'from_date',
        'to_date',
        'description',
        'redeem_link',
        'created_at',
        'updated_at',
    ];
}
