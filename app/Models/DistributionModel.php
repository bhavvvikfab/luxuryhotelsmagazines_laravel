<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionModel extends Model
{
    use HasFactory;
    protected $table = "distribution";
    protected $fillable = [
        'title',
        'description',
        'hotel_image',
        'hotel_description',
        'created_at',
        'updated_at',
    ];
}
