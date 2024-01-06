<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    use HasFactory;
    public $timestamps = FALSE;
    
    protected $table = "country";
    protected $fillable = [
        'country',
        'created_at',
        'updated_at',
    ];

    public function hotel_contacts() {
        return $this->belongsTo(HotelModel::class);
    }

}