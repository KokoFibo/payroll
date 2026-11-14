<?php

namespace App\Exceptions;

/** Akun presensi gagal dibuat (API presensi menolak atau tidak bisa dihubungi). Pesannya aman ditampilkan ke admin. */
class PresensiUserException extends \RuntimeException
{
}
