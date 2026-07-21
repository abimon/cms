<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_path',
        'church_id',
        'category',
        'status',
    ];
    public function church()
    {
        return $this->belongsTo(Church::class);
    }
}
