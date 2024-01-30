<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageModel extends Model
{
    use HasFactory;
    protected $table = "packages";
    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
    ];


}
