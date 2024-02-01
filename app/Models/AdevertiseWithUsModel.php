<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdevertiseWithUsModel extends Model
{
    use HasFactory;
    protected $table = "adevertise_with_us";
    protected $fillable = [
        'type',
        'details',
        'created_at',
        'updated_at',
    ];
}
