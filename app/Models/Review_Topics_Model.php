<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review_Topics_Model extends Model
{
    use HasFactory;
    protected $table = "review_topics";
    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
    ];
}
