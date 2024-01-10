<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPageAddonModel extends Model
{
    use HasFactory;

    protected $table = "home_page_addon";
    protected $fillable = [
        'hotel_id',
        'home_page_latest_news',
        'hotel_latest_news',
        'special_offer_to_homepage',
        'home_page_spotlight',
        'created_at',
        'updated_at',
    ];

    public function hotels()
    {
        return $this->belongsTo(HotelModel::class, 'hotel_id');
    }
}
