<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateDefiRequest extends FormRequest
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
            'titre' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-_\.\:\!\?]+$/u',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],
            'date_debut' => [
                'required',
                'date',
                'after_or_equal:today',
                'before:2030-12-31',
            ],
            'date_fin' => [
                'required',
                'date',
                'after:date_debut',
                'before:2030-12-31',
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
            'titre.required' => 'Le titre du défi est obligatoire.',
            'titre.min' => 'Le titre doit contenir au moins 3 caractères.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'titre.regex' => 'Le titre contient des caractères non autorisés.',
            
            'description.required' => 'La description du défi est obligatoire.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être au format valide.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_debut.before' => 'La date de début ne peut pas être après 2030.',
            
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.date' => 'La date de fin doit être au format valide.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'date_fin.before' => 'La date de fin ne peut pas être après 2030.',
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
            'titre' => 'titre',
            'description' => 'description',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'titre' => trim($this->titre),
            'description' => trim($this->description),
        ]);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional validation for date range
            if ($this->date_debut && $this->date_fin) {
                $dateDebut = Carbon::parse($this->date_debut);
                $dateFin = Carbon::parse($this->date_fin);
                
                // Check if the challenge duration is reasonable (not more than 1 year)
                if ($dateFin->diffInDays($dateDebut) > 365) {
                    $validator->errors()->add('date_fin', 'La durée du défi ne peut pas dépasser 365 jours.');
                }
                
                // Check if the challenge duration is at least 1 day
                if ($dateFin->diffInDays($dateDebut) < 1) {
                    $validator->errors()->add('date_fin', 'La durée du défi doit être d\'au moins 1 jour.');
                }
            }
        });
    }
}