<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;

/** Perusahaan di DATABASE APPLICANT (tabel `companies` milik portal). */
class Company extends Model
{
    protected $connection = Applicant::CONNECTION;
    protected $table = 'companies';
    protected $guarded = [];
}
