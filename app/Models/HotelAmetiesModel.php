<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAmetiesModel extends Model
{
    use HasFactory;

    protected $table = "hotel_amieties";
    protected $fillable = [
        'title',
        'type',
        'image',
        'created_at',
        'updated_at',
    ];
}
