<?php

namespace App\Enums;

enum ApplicantStatus: int
{
    case Melamar    = 1;
    case Komunikasi = 2;
    case Psikotest  = 3;
    case Interview  = 4;
    case Ditolak    = 5;
    case Cadangan   = 6;
    case Onboarding = 7;
    case Diterima   = 8;

    public function label(): string
    {
        return match ($this) {
            self::Melamar    => 'Melamar',
            self::Komunikasi => 'Sedang Komunikasi',
            self::Psikotest  => 'Psikotest',
            self::Interview  => 'Interview',
            self::Ditolak    => 'Ditolak',
            self::Cadangan   => 'Cadangan',
            self::Onboarding => 'Onboarding',
            self::Diterima   => 'Diterima',
        };
    }

    /** Kelas badge Bootstrap 5. */
    public function color(): string
    {
        return match ($this) {
            self::Melamar    => 'bg-secondary',
            self::Komunikasi => 'bg-info text-dark',
            self::Psikotest  => 'bg-primary',
            self::Interview  => 'bg-warning text-dark',
            self::Ditolak    => 'bg-danger',
            self::Cadangan   => 'bg-dark',
            self::Onboarding => 'bg-info text-dark',
            self::Diterima   => 'bg-success',
        };
    }

    /** @return int[] */
    public static function values(): array
    {
        return array_map(fn (self $s) => $s->value, self::cases());
    }
}
