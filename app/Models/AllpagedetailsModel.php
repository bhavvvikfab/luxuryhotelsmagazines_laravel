<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllpagedetailsModel extends Model
{
    use HasFactory;
    protected $table = "all_page_details";
    protected $fillable = [
        'type',
        'details',
        'created_at',
        'updated_at',
    ];
}
