<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertiesModel extends Model
{
    use HasFactory;
    protected $table = "properties";
    protected $fillable = [
        'country_name',
        'hotel_thumbnail',
        'property_title',
        'property_website',
        'hotel_video',
        'you_tube_link',
        'short_description',
        'long_description',
        'created_at',
        'updated_at',
    ];
}
