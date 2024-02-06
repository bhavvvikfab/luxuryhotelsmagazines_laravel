<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategoryModel extends Model
{
    use HasFactory;
    protected $table = "news_category";
    protected $fillable = [
        'news_category',
        'created_at',
        'updated_at',
    ];
}
