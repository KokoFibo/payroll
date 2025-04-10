<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedFileExtension implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        \Log::info('File info', [
            'class' => get_class($value),
            'originalName' => $value?->getClientOriginalName(),
            'mimeType' => $value?->getMimeType(),
            'realMimeType' => $value?->getPathname() ? mime_content_type($value->getPathname()) : null,
            'extension' => $value?->getClientOriginalExtension(),
            'exists' => $value && file_exists($value->getPathname()),
        ]);
        if (!$value instanceof \Illuminate\Http\UploadedFile) {
            $fail('File tidak valid.');
            return;
        }

        if (!Str::startsWith($value->getMimeType(), 'image/')) {
            $fail('File harus berupa gambar.');
        }
    }
}
