<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionDetailsModel extends Model
{
    use HasFactory;
    protected $table = "distribution_details";
    protected $fillable = [
        'main_page_title',
        'title',
        'sub_title',
        'created_at',
        'updated_at',
    ];
}
