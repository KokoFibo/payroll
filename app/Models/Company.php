<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }
}
