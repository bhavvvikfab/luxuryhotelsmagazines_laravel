<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionDataModel extends Model
{
    use HasFactory;
    protected $table = "distributions_data";
    protected $fillable = [
        'header_info',
        'services_data',
        'created_at',
        'updated_at',
    ];
}
