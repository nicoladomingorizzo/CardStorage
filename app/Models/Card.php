<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function expansion()
    {
        return $this->belongsTo(Expansion::class);
    }
}
