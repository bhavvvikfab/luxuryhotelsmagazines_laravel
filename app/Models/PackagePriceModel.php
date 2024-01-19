<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePriceModel extends Model
{
    use HasFactory;
    protected $table = "package_price";
    protected $fillable = [
        'package_catagory',
        'package_name',
        'package_original_price',
        'package_price',
        'hotel_package_price',
        'package_validity',
        'package_validity_title',
        'package_inner_title',
        'package_inner_sub_title',
        'package_inner_content',
        'package_expiry_date',
        'package_action',
        'created_at',
        'updated_at',
    ];
}
