<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $table = "news";
    protected $fillable = [
        'user_id',
        'news_type',
        'business_name',
        'country',
        'full_name',
        'email_address',
        'news_title',
        'news_desc',
        'news_image',
        'status',
        'catagory',
        'editor_choice',
        // 'phone_number',
        'news_views',
        'news_likes',
        'youtube_link',
        'youtube_shorts',
    ];

    public function special_offer()
    {
        return $this->hasMany(HotelSpecialOfferModel::class);
       
    }
}
