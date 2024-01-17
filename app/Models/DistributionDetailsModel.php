<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionDetailsModel extends Model
{
    use HasFactory;
    protected $table = "distribution_details";
    protected $fillable = [
        'title',
        'hotel_image',
        'link',
        'created_at',
        'updated_at',
    ];
}
