<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name',
        'hp',
        'type',
        'rarity',
        'price',
        'description',
        'expansion_id'
    ];

    public function expansion()
    {
        return $this->belongsTo(Expansion::class);
    }

    public function images()
    {
        return $this->hasMany(CardImage::class);
    }
}
