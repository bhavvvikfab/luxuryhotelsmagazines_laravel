<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagazinesModel extends Model
{
    use HasFactory;

    protected $table = "magazines";
    protected $fillable = [
        'title',
        'thumbnail',
        'file_pdf',
        'created_at',
        'updated_at',
    ];

}
