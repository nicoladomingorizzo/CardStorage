<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardImage extends Model
{
    protected $fillable = ['card_id', 'path'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
