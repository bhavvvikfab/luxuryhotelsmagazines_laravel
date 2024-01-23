<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;

    protected $table = "payment";
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'payment_type',
        'charge_subscription_id',
        'start_date',
        'end_date',
        'total_days',
        'transaction_id',
        'payment_status'
    ];
}
