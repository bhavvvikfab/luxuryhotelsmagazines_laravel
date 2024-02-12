<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotingDetailsModel extends Model
{
    use HasFactory;
    protected $table = "voting_details";
    protected $fillable = [
        'hotel_id',
        'news_id',
        'type',
        'name',
        'email',
        'description',
        'created_at',
        'updated_at'
    ];

    public function hotels()
    {
        return $this->belongsTo(HotelModel::class, 'hotel_id');
    }
    // public function hotels()
    // {
    //     return $this->hasMany(HotelModel::class, 'id');
    //     // Replace 'foreign_key' with the actual foreign key column name
    // }

}
