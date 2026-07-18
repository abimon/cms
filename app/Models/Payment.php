<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable =[
        'user_id',
        'account_id',
        'payment_code',
        'amount',
        'payment_method',
        'status'
    ];
}
