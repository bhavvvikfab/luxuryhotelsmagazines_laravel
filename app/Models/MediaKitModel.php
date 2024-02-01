<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaKitModel extends Model
{
    use HasFactory;
    protected $table = "media_kit";
    protected $fillable = [
        'title',
        'media_kit_image',
        'file_pdf',
        'created_at',
        'updated_at',
    ];
}
