<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review_Category_Model extends Model
{
    use HasFactory;
    protected $table = "review_categories";
    protected $fillable = [
        'title',
        'max_rating',
        'sort_order',
        'created_at',
        'updated_at',
    ];
}
