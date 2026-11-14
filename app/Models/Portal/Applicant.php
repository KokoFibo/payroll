<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;

/**
 * Pelamar di DATABASE APPLICANT (koneksi "applicant"), bukan database payroll.
 * Skema dimiliki portal pelamar; aplikasi admin hanya membaca, mengubah status,
 * menghapus, dan memulihkan baris ini.
 */
class Applicant extends Model
{
    public const CONNECTION = 'applicant';

    protected $connection = self::CONNECTION;
    protected $table = 'applicantdatas';
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
}
