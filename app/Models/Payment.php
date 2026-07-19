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
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function account(){
        return $this->belongsTo(Account::class,'account_id');
    }
}
