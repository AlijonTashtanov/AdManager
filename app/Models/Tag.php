<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    // E'lonlar bilan ko'p-ko'p aloqa
    public function ads()
    {
        return $this->belongsToMany(Ad::class, 'ad_tag');
    }
}
