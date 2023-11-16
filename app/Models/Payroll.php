<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $guarded = [];




    public function jamkerjaid () {
        return $this->belongsTo(Jamkerjaid::class);
    }

    public function karyawan () {
        return $this->belongsTo(Karyawan::class);
    }
}
