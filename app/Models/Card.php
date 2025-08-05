<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_id',
        'cardholder_name',
        'card_number',
        'card_type',
        'expiry_date',
        'csc',
        'org_bank_id',
        'org_name',
        'org_company_id',
        'payment_status',
        'status',
    ];
}
