<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Catatan penerimaan pelamar yang masih bisa dibatalkan (undo).
 * Snapshot berisi data pribadi (terenkripsi) dan dihapus oleh
 * `applicants:finalize-acceptances` setelah masa undo habis.
 */
class ApplicantAcceptance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
        'files'      => 'array',
    ];

    public function scopeUndoable(Builder $q): Builder
    {
        return $q->where('expires_at', '>', now());
    }

    public function isUndoable(): bool
    {
        return $this->expires_at->isFuture();
    }
}
