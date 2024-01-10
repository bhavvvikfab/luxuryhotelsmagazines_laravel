<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelContactsModel extends Model
{
    use HasFactory;

    protected $table = "hotel_contacts";
    protected $fillable = [
        'hotel_id',
        'hotel_title',
        'name',
        'email',
        'contact_no',
        'created_at',
        'updated_at',
    ];


    public function hotels()
    {
        return $this->belongsTo(HotelModel::class, 'hotel_id');
    }
}
