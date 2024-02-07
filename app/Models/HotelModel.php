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
        'country',
        'hotel_title',
        'address',
        'about_hotel',
        'hotel_images',
        'amities',
        'facilities',
        'rooms_and_suites',
        'restaurent_bars',
        'spa_wellness',
        'other_facilities',
        'aditional_information',
        'subscription_package',
        'hotel_news',
        'youtube_link',
        'website',
        'contact_no',
        'otherInformation1',
        'otherInformation2',
        'created_at',
        'updated_at',
    ];

    public function country()
{
    return $this->belongsTo(CountryModel::class, 'country_id');
}

public function hotel_contacts() {
    return $this->hasOne(HotelContactsModel::class, 'hotel_id');
    
}


public function home_page_addon() {
    return $this->hasOne(HotelPageAddonModel::class, 'hotel_id');
    
}

public function special_offer() {
    return $this->hasOne(HotelSpecialOfferModel::class, 'hotel_id');
   
}


}
