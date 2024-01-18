<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs_Model extends Model
{
    use HasFactory;
    protected $table = "about_us";
    protected $fillable = [
        'about_us',
        'magzine_distributed',
        'distributed_desc',
    ];
}
