<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvisRequest extends FormRequest
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
            'livre_id' => [
                'required',
                'integer',
                'exists:livres,id'
            ],
            'note' => [
                'required',
                'integer',
                'min:1',  // Minimum 1 étoile
                'max:5'   // Maximum 5 étoiles
            ],
            'commentaire' => [
                'required',
                'string',
                'min:10',  // Minimum 10 caractères
                'max:1000', // Maximum 1000 caractères
                'regex:/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/u' // Caractères autorisés
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'livre_id.required' => 'L\'ID du livre est obligatoire.',
            'livre_id.integer' => 'L\'ID du livre doit être un nombre entier.',
            'livre_id.exists' => 'Le livre spécifié n\'existe pas.',
            
            'note.required' => 'La note est obligatoire.',
            'note.integer' => 'La note doit être un nombre entier.',
            'note.min' => 'La note doit être d\'au moins 1 étoile.',
            'note.max' => 'La note ne peut pas dépasser 5 étoiles.',
            
            'commentaire.required' => 'Le commentaire est obligatoire.',
            'commentaire.min' => 'Le commentaire doit contenir au moins 10 caractères.',
            'commentaire.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
            'commentaire.regex' => 'Le commentaire contient des caractères non autorisés.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'livre_id' => 'livre',
            'note' => 'note',
            'commentaire' => 'commentaire',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $note = $this->input('note');
            $commentaire = $this->input('commentaire');
            
            // Validation spécifique pour la note
            if ($note !== null) {
                // S'assurer que la note est bien entre 1 et 5
                if ($note < 1 || $note > 5) {
                    $validator->errors()->add('note', 'La note doit être comprise entre 1 et 5 étoiles.');
                }
                
                // Validation pour les notes extrêmes
                if ($note == 1) {
                    // Pour une note de 1 étoile, s'assurer que le commentaire explique pourquoi
                    if ($commentaire && strlen(trim($commentaire)) < 20) {
                        $validator->errors()->add('commentaire', 'Pour une note de 1 étoile, veuillez expliquer en détail les raisons de votre évaluation (minimum 20 caractères).');
                    }
                }
            }
            
            if ($commentaire) {
                // Check for inappropriate content (basic profanity filter)
                $inappropriateWords = ['spam', 'test', 'temp', 'fake', 'hate', 'stupid']; // Add more as needed
                foreach ($inappropriateWords as $word) {
                    if (stripos($commentaire, $word) !== false) {
                        $validator->errors()->add('commentaire', 'Le commentaire ne peut pas contenir de mots inappropriés.');
                        break;
                    }
                }
                
                // Check for excessive repetition
                if (preg_match('/(.)\1{8,}/', $commentaire)) {
                    $validator->errors()->add('commentaire', 'Le commentaire ne peut pas contenir de caractères répétés plus de 8 fois.');
                }
                
                // Check for minimum meaningful content
                $meaningfulContent = preg_replace('/[\s\p{P}]+/u', '', $commentaire);
                if (strlen($meaningfulContent) < 5) {
                    $validator->errors()->add('commentaire', 'Le commentaire doit contenir au moins 5 caractères significatifs.');
                }
                
                // Check for excessive use of special characters
                $specialCharCount = preg_match_all('/[!@#$%^&*()_+=\[\]{}|;:,.<>?]/', $commentaire);
                if ($specialCharCount > strlen($commentaire) * 0.3) {
                    $validator->errors()->add('commentaire', 'Le commentaire contient trop de caractères spéciaux.');
                }
                
                // Check for all caps (spam detection)
                if (strlen($commentaire) > 20 && strtoupper($commentaire) === $commentaire) {
                    $validator->errors()->add('commentaire', 'Le commentaire ne peut pas être entièrement en majuscules.');
                }
            }
        });
    }
}