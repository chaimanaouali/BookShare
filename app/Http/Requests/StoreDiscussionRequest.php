<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscussionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()]+$/u', // Allow letters, numbers, spaces, and common punctuation
            ],
            'contenu' => [
                'required',
                'string',
                'min:10',
                'max:5000',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/u' // Allow multiline text with common punctuation
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre de la discussion est obligatoire.',
            'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'titre.regex' => 'Le titre contient des caractères non autorisés. Utilisez uniquement des lettres, chiffres, espaces et ponctuation courante.',
            
            'contenu.required' => 'Le contenu de la discussion est obligatoire.',
            'contenu.min' => 'Le contenu doit contenir au moins 10 caractères.',
            'contenu.max' => 'Le contenu ne peut pas dépasser 5000 caractères.',
            'contenu.regex' => 'Le contenu contient des caractères non autorisés.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'titre' => 'titre de la discussion',
            'contenu' => 'contenu de la discussion',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $titre = $this->input('titre');
            $contenu = $this->input('contenu');
            
            if ($titre) {
                // Check for inappropriate content (basic profanity filter)
                $inappropriateWords = ['spam', 'test', 'temp', 'fake']; // Add more as needed
                foreach ($inappropriateWords as $word) {
                    if (stripos($titre, $word) !== false) {
                        $validator->errors()->add('titre', 'Le titre ne peut pas contenir de mots inappropriés.');
                        break;
                    }
                }
                
                // Check for excessive repetition
                if (preg_match('/(.)\1{4,}/', $titre)) {
                    $validator->errors()->add('titre', 'Le titre ne peut pas contenir de caractères répétés plus de 4 fois.');
                }
                
                // Check for all caps (spam detection)
                if (strlen($titre) > 10 && strtoupper($titre) === $titre) {
                    $validator->errors()->add('titre', 'Le titre ne peut pas être entièrement en majuscules.');
                }
            }
            
            if ($contenu) {
                // Check for inappropriate content in content
                $inappropriateWords = ['spam', 'test', 'temp', 'fake'];
                foreach ($inappropriateWords as $word) {
                    if (stripos($contenu, $word) !== false) {
                        $validator->errors()->add('contenu', 'Le contenu ne peut pas contenir de mots inappropriés.');
                        break;
                    }
                }
                
                // Check for excessive repetition in content
                if (preg_match('/(.)\1{10,}/', $contenu)) {
                    $validator->errors()->add('contenu', 'Le contenu ne peut pas contenir de caractères répétés plus de 10 fois.');
                }
                
                // Check for minimum meaningful content (not just spaces and punctuation)
                $meaningfulContent = preg_replace('/[\s\p{P}]+/u', '', $contenu);
                if (strlen($meaningfulContent) < 5) {
                    $validator->errors()->add('contenu', 'Le contenu doit contenir au moins 5 caractères significatifs.');
                }
            }
        });
    }
}