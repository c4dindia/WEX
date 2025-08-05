<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'card_id',
        'transaction_id',
        'transaction_date',
        'posting_date',
        'billing_amount',
        'transaction_type',
        'merchant_description',
        'is_credit',
        'status',
    ];
}
