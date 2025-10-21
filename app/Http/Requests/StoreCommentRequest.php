<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
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
            'contenu' => [
                'required',
                'string',
                'min:3',
                'max:2000',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/u' // Allow multiline text with common punctuation
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:comments,id'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'contenu.required' => 'Le contenu du commentaire est obligatoire.',
            'contenu.min' => 'Le commentaire doit contenir au moins 3 caractères.',
            'contenu.max' => 'Le commentaire ne peut pas dépasser 2000 caractères.',
            'contenu.regex' => 'Le commentaire contient des caractères non autorisés.',
            
            'parent_id.integer' => 'L\'ID du commentaire parent doit être un nombre entier.',
            'parent_id.exists' => 'Le commentaire parent spécifié n\'existe pas.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'contenu' => 'contenu du commentaire',
            'parent_id' => 'commentaire parent',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $contenu = $this->input('contenu');
            
            if ($contenu) {
                // Check for inappropriate content (basic profanity filter)
                $inappropriateWords = ['spam', 'test', 'temp', 'fake', 'hate']; // Add more as needed
                foreach ($inappropriateWords as $word) {
                    if (stripos($contenu, $word) !== false) {
                        $validator->errors()->add('contenu', 'Le commentaire ne peut pas contenir de mots inappropriés.');
                        break;
                    }
                }
                
                // Check for excessive repetition
                if (preg_match('/(.)\1{8,}/', $contenu)) {
                    $validator->errors()->add('contenu', 'Le commentaire ne peut pas contenir de caractères répétés plus de 8 fois.');
                }
                
                // Check for minimum meaningful content
                $meaningfulContent = preg_replace('/[\s\p{P}]+/u', '', $contenu);
                if (strlen($meaningfulContent) < 2) {
                    $validator->errors()->add('contenu', 'Le commentaire doit contenir au moins 2 caractères significatifs.');
                }
                
                // Check for excessive use of special characters
                $specialCharCount = preg_match_all('/[!@#$%^&*()_+=\[\]{}|;:,.<>?]/', $contenu);
                if ($specialCharCount > strlen($contenu) * 0.3) {
                    $validator->errors()->add('contenu', 'Le commentaire contient trop de caractères spéciaux.');
                }
            }
        });
    }
}