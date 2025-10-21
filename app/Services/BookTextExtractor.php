<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookTextExtractor
{
    /**
     * Extract text from uploaded book file.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function extractText(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        switch ($extension) {
            case 'txt':
                return $this->extractFromTxt($file);
            case 'pdf':
                return $this->extractFromPdf($file);
            case 'epub':
            case 'mobi':
                return $this->extractFromEbook($file);
            default:
                return '';
        }
    }

    /**
     * Extract text from TXT file.
     */
    private function extractFromTxt(UploadedFile $file): string
    {
        return $file->get();
    }

    /**
     * Extract text from PDF file (basic implementation).
     */
    private function extractFromPdf(UploadedFile $file): string
    {
        // For now, return a placeholder. In production, you'd use a PDF parser like smalot/pdfparser
        // This is a simple fallback that extracts basic text patterns
        $content = $file->get();
        
        // Basic PDF text extraction (very limited)
        // Remove PDF binary data and extract readable text
        $text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', '', $content);
        $text = preg_replace('/\s+/', ' ', $text);
        
        // If we can't extract meaningful text, return the filename as a fallback
        if (strlen(trim($text)) < 50) {
            return 'PDF: ' . $file->getClientOriginalName();
        }
        
        return $text;
    }

    /**
     * Extract text from EPUB/MOBI files (placeholder).
     */
    private function extractFromEbook(UploadedFile $file): string
    {
        // For now, return filename as placeholder
        // In production, you'd use libraries like epub-reader or mobi-reader
        return 'Ebook: ' . $file->getClientOriginalName();
    }

    /**
     * Check if extracted text has sufficient content for similarity analysis.
     *
     * @param string $text
     * @return bool
     */
    public function hasSufficientContent(string $text): bool
    {
        return strlen(trim($text)) >= 200;
    }
}
