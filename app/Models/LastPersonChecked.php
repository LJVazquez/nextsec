<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastPersonChecked extends Model
{
    use HasFactory;

    public function hunterData()
    {
        return $this->belongsTo(hunterData::class);
    }
}
