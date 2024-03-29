<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueriesModel extends Model
{
    use HasFactory;
    protected $table = "queries";
    protected $fillable = [
        'name',
        'email',
        'message',
        'reply_query_msg',
        'status',
        'created_at',
        'updated_at',
    ];
}
