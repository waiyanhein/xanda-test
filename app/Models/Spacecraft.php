<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Spacecraft extends Model
{
    const STATUS_OPERATIONAL = 1;
    const STATUS_DAMAGED = 2;

    const CLASSES = [
        'Star Destroyer',
        'Meteoroid Breaker',
        'Moon Lander',
        'Planet Invader'
    ];

    protected $fillable = [
        'name',
        'class',
        'crew',
        'value',
        'status'
    ];

    public function getStatusLabelAttribute()
    {
        return $this->status == static::STATUS_OPERATIONAL ? "operational": "damaged";
    }

    public function getPrettyValueAttribute()
    {
        return number_format($this->value, 2);
    }

    public function getPrettyCrewAttribute()
    {
        return number_format($this->crew);
    }

    public function armaments()
    {
        return $this->hasMany(Armament::class);
    }

    public function trash()
    {
        $this->deleteImageFile();
        $this->delete();
    }

    public function deleteImageFile()
    {
        if (Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
    }

    //this can be a local scope query too.
    public static function search($data)
    {
        $spacecrafts = Spacecraft::where('id', '!=', 0);

        if (isset($data['name']) and $data['name'] != '_') {
            $spacecrafts = $spacecrafts->where('name', 'LIKE', '%' . $data['name'] . '%');
        }

        if (isset($data['class']) and $data['class'] != "_") {
            $spacecrafts = $spacecrafts->where('class', 'LIKE', '%' . $data['class'] . '%');
        }

        if (isset($data['status']) and $data['status'] > 0) {
            $spacecrafts = $spacecrafts->where('status', $data['status']);
        }

        return $spacecrafts->get();
    }
}
