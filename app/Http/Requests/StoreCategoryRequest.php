<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:categories,nom',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-_\.]+$/u', // Allow letters, numbers, spaces, hyphens, underscores, dots
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
                'min:10',
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
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.min' => 'Le nom de la catégorie doit contenir au moins 2 caractères.',
            'nom.max' => 'Le nom de la catégorie ne peut pas dépasser 255 caractères.',
            'nom.unique' => 'Une catégorie avec ce nom existe déjà.',
            'nom.regex' => 'Le nom de la catégorie contient des caractères non autorisés.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
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
            'nom' => 'nom de la catégorie',
            'description' => 'description',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nom' => trim($this->nom),
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }
}