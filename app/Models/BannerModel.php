<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerModel extends Model
{
    use HasFactory;

    // public $timestamps = FALSE;
    
    protected $table = "banner";
    protected $fillable = [
        'business_name',
        'business_link',
        'email',
        'title',
        'banner_catagory',
        'banner_type',
        'image',
        'you_tube_link',
        'created_at',
        'updated_at',
    ];
}
