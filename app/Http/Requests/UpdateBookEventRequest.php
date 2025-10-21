<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'defi_id' => [
                'nullable',
                'integer',
                'exists:defis,id',
            ],
            'type' => [
                'required',
                'string',
                Rule::in(['Silent Reading Session', 'Reading Challenge', 'Book Club Meeting']),
            ],
            'titre' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-_\.\:\!\?]+$/u',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
                'min:10',
            ],
            'date_evenement' => [
                'required',
                'date',
                'after_or_equal:today',
                'before:2030-12-31',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'defi_id.integer' => 'L\'ID du défi doit être un nombre entier.',
            'defi_id.exists' => 'Le défi sélectionné n\'existe pas.',
            
            'type.required' => 'Le type d\'événement est obligatoire.',
            'type.in' => 'Le type d\'événement doit être : Silent Reading Session, Reading Challenge ou Book Club Meeting.',
            
            'titre.required' => 'Le titre de l\'événement est obligatoire.',
            'titre.min' => 'Le titre doit contenir au moins 3 caractères.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'titre.regex' => 'Le titre contient des caractères non autorisés.',
            
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            
            'date_evenement.required' => 'La date de l\'événement est obligatoire.',
            'date_evenement.date' => 'La date doit être au format valide.',
            'date_evenement.after_or_equal' => 'La date de l\'événement ne peut pas être dans le passé.',
            'date_evenement.before' => 'La date de l\'événement ne peut pas être après 2030.',
            
            'image.image' => 'Le fichier doit être une image valide.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou WebP.',
            'image.max' => 'L\'image ne peut pas dépasser 2MB.',
            'image.dimensions' => 'L\'image doit avoir une taille entre 100x100 et 5000x5000 pixels.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'defi_id' => 'défi',
            'type' => 'type d\'événement',
            'titre' => 'titre',
            'description' => 'description',
            'date_evenement' => 'date',
            'image' => 'image',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'titre' => trim($this->titre),
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }
}