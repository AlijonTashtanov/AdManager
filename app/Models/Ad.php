<?php

// app/Models/Ad.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = ['user_id', 'category_id', 'region_id', 'title', 'description', 'price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ad_tag');
    }
}
