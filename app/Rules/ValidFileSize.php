<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidFileSize implements ValidationRule
{
    protected int $maxSizeInMB;

    public function __construct(int $maxSizeInMB = 10)
    {
        $this->maxSizeInMB = $maxSizeInMB;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values (nullable)
        }

        if (!$value instanceof \Illuminate\Http\UploadedFile) {
            $fail('Le fichier doit être un fichier valide.');
            return;
        }

        $fileSizeInMB = $value->getSize() / (1024 * 1024);

        if ($fileSizeInMB > $this->maxSizeInMB) {
            $fail("Le fichier ne peut pas dépasser {$this->maxSizeInMB}MB. Taille actuelle: " . number_format($fileSizeInMB, 2) . "MB.");
        }
    }
}