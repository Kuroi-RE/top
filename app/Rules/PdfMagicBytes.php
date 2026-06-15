<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class PdfMagicBytes implements ValidationRule
{
    private const PDF_MAGIC = '%PDF';

    /**
     * Run the validation rule.
     *
     * Opens the uploaded file in binary mode, reads the first 4 bytes, and
     * compares them against the PDF magic bytes signature (%PDF / 25 50 44 46).
     * This prevents files with a .pdf extension but non-PDF content from passing
     * validation.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!($value instanceof UploadedFile)) {
            $fail('The :attribute must be a file.');
            return;
        }

        $handle = fopen($value->getRealPath(), 'rb');
        $header = fread($handle, 4);
        fclose($handle);

        if ($header !== self::PDF_MAGIC) {
            $fail('The :attribute content does not match the expected PDF format.');
        }
    }
}
