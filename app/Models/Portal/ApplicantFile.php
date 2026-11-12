<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;

/** Dokumen pelamar di DATABASE APPLICANT. Kolom `type` = ktp, kk, ijazah, dst. */
class ApplicantFile extends Model
{
    protected $connection = Applicant::CONNECTION;
    protected $table = 'applicantfiles';
    protected $guarded = [];
}
