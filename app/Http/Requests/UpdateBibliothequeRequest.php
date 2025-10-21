<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBibliothequeRequest extends FormRequest
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
        $bibliothequeId = $this->route('bibliotheque')->id ?? null;
        
        return [
            'nom_bibliotheque' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()]+$/u', // Allow letters, numbers, spaces, and common punctuation
                Rule::unique('bibliotheque_virtuelles', 'nom_bibliotheque')
                    ->where('user_id', auth()->id())
                    ->whereNull('deleted_at')
                    ->ignore($bibliothequeId)
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
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
            'nom_bibliotheque.required' => 'Le nom de la bibliothèque est obligatoire.',
            'nom_bibliotheque.min' => 'Le nom de la bibliothèque doit contenir au moins 3 caractères.',
            'nom_bibliotheque.max' => 'Le nom de la bibliothèque ne peut pas dépasser 255 caractères.',
            'nom_bibliotheque.regex' => 'Le nom de la bibliothèque contient des caractères non autorisés. Utilisez uniquement des lettres, chiffres, espaces et ponctuation courante.',
            'nom_bibliotheque.unique' => 'Vous avez déjà une bibliothèque avec ce nom. Choisissez un nom différent.',
            
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'description.regex' => 'La description contient des caractères non autorisés.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom_bibliotheque' => 'nom de la bibliothèque',
            'description' => 'description',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Additional custom validation logic can be added here
            $nomBibliotheque = $this->input('nom_bibliotheque');
            
            if ($nomBibliotheque) {
                // Check for inappropriate content (basic profanity filter)
                $inappropriateWords = ['test', 'temp', 'temporary']; // Add more as needed
                foreach ($inappropriateWords as $word) {
                    if (stripos($nomBibliotheque, $word) !== false) {
                        $validator->errors()->add('nom_bibliotheque', 'Le nom de la bibliothèque ne peut pas contenir de mots inappropriés.');
                        break;
                    }
                }
                
                // Check for excessive repetition
                if (preg_match('/(.)\1{4,}/', $nomBibliotheque)) {
                    $validator->errors()->add('nom_bibliotheque', 'Le nom de la bibliothèque ne peut pas contenir de caractères répétés plus de 4 fois.');
                }
            }
        });
    }
}