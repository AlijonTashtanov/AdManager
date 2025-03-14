<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name'];

    // E'lonlar bilan aloqa
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
