<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armament extends Model
{
    protected $fillable = [
        'title',
        'qty'
    ];

    public function spacecraft()
    {
        return $this->belongsTo(Spacecraft::class);
    }
}
