<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'business_name',
        'full_name',
        'email_address',
        'news_title',
        'news_desc',
        'news_image',
        'youtube_link',
    ];

    public function special_offer()
    {
        return $this->hasMany(HotelSpecialOfferModel::class);
       
    }
}
