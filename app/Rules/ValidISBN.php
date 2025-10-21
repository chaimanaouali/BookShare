<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidISBN implements ValidationRule
{
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

        // Remove hyphens and spaces
        $isbn = preg_replace('/[-\s]/', '', $value);
        
        // Check if it's a valid ISBN-10 or ISBN-13
        if (!$this->isValidISBN10($isbn) && !$this->isValidISBN13($isbn)) {
            $fail('L\'ISBN doit être un ISBN-10 ou ISBN-13 valide.');
        }
    }

    /**
     * Validate ISBN-10
     */
    private function isValidISBN10(string $isbn): bool
    {
        if (strlen($isbn) !== 10) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            if (!is_numeric($isbn[$i])) {
                return false;
            }
            $sum += (int)$isbn[$i] * (10 - $i);
        }

        $checkDigit = $isbn[9];
        if ($checkDigit === 'X' || $checkDigit === 'x') {
            $checkDigit = 10;
        } elseif (!is_numeric($checkDigit)) {
            return false;
        } else {
            $checkDigit = (int)$checkDigit;
        }

        return ($sum + $checkDigit) % 11 === 0;
    }

    /**
     * Validate ISBN-13
     */
    private function isValidISBN13(string $isbn): bool
    {
        if (strlen($isbn) !== 13) {
            return false;
        }

        if (!preg_match('/^\d{13}$/', $isbn)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$isbn[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10;
        return $checkDigit === (int)$isbn[12];
    }
}