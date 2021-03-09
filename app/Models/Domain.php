<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function intelx()
    {
        return $this->hasMany(IntelxData::class);
    }

    public function hunterDomains()
    {
        return $this->hasMany(HunterData::class);
    }
}
