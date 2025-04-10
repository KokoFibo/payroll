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

        dd([
            'ext' => $value->getClientOriginalExtension(),
            'mime' => $value->getMimeType(),
            'real_mime' => mime_content_type($value->getPathname()),
            'path' => $value->getPathname(),
            'is_file' => file_exists($value->getPathname()),
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
