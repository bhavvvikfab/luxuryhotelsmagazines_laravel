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
        'phone_number',
        'contact_no',
        'from_date',
        'to_date',
        'description',
        'special_offer',
        'reedem_link',
        'created_at',
        'updated_at',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
    public function hotels()
    {
        return $this->belongsTo(HotelModel::class, 'hotel_id');
    }
}
