<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expansion extends Model
{
    protected $fillable = ['name', 'serie', 'release_date'];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
