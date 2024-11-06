<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PdfsCheck implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($value as $key => $pdf) {
            if (!is_array($pdf)) {
                if (isset($pdf)) {
                    if ($pdf->getSize() > 52428800) {
                        $fail('PDFファイル（' . $key + 1 . ')はファイルサイズが50MBを超えています。');
                    }
                    if ($pdf->getMimeType() !== "application/pdf") {
                        $fail('PDFファイル（' . $key + 1 . ')はアップロードされたファイルがPDF形式ではありません。');
                    }
                }
            }
        }
    }
}
