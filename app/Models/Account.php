<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'type',
        'target',
        'status',
        'created_by',
        'church_id'
    ];
    public function parent_account()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }
    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
