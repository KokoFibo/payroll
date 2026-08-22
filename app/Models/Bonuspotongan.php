<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonuspotongan extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'bonuspotongans';
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
