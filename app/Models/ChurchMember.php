<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchMember extends Model
{
    protected $fillable = [
        'member_id',
        'church_id',
    ];
}
