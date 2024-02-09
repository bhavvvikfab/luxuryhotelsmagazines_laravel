<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest_Category_Model extends Model
{
    use HasFactory;
    protected $table = "guest_review";
    protected $fillable = [
        'type',
        'category_rating',
        'name',
        'email',
        'description',
        'created_at',
        'updated_at',
    ];
}
