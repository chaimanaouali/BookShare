<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $livre = $this->route('livre');
        return auth()->check() && $livre && $livre->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-_\.\:\!\?]+$/u',
            ],
            'author' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-_\.]+$/u',
            ],
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\-]+$/',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
                'min:10',
            ],
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB
            ],
            'publication_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
                'after:1900-01-01',
            ],
            'genre' => [
                'nullable',
                'string',
                'max:100',
            ],
            'fichier_livre' => [
                'nullable',
                'file',
                'mimes:pdf,epub,mobi,txt,doc,docx',
                'max:10240', // 10MB
            ],
            'format' => [
                'nullable',
                'string',
                'max:50',
                Rule::in(['PDF', 'EPUB', 'MOBI', 'TXT', 'DOC', 'DOCX']),
            ],
            'visibilite' => [
                'required',
                'string',
                Rule::in(['public', 'private']),
            ],
            'user_description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'categorie_id' => [
                'nullable',
                'exists:categories,id',
            ],
            'langue' => [
                'nullable',
                'string',
                'max:10',
                Rule::in(['fr', 'en', 'es', 'de', 'it', 'pt', 'ar', 'zh', 'ja', 'ru']),
            ],
            'nb_pages' => [
                'nullable',
                'integer',
                'min:1',
                'max:10000',
            ],
            'resume' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'etat' => [
                'nullable',
                'string',
                'max:50',
                Rule::in(['neuf', 'bon', 'moyen', 'mauvais']),
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
            'title.required' => 'Le titre du livre est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins 2 caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'title.regex' => 'Le titre contient des caractères non autorisés.',
            
            'author.required' => 'L\'auteur est obligatoire.',
            'author.min' => 'Le nom de l\'auteur doit contenir au moins 2 caractères.',
            'author.max' => 'Le nom de l\'auteur ne peut pas dépasser 255 caractères.',
            'author.regex' => 'Le nom de l\'auteur contient des caractères non autorisés.',
            
            'isbn.regex' => 'L\'ISBN doit contenir uniquement des chiffres et des tirets.',
            'isbn.max' => 'L\'ISBN ne peut pas dépasser 20 caractères.',
            
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            
            'cover_image.image' => 'L\'image de couverture doit être une image valide.',
            'cover_image.mimes' => 'L\'image de couverture doit être au format JPEG, PNG, JPG, GIF ou WebP.',
            'cover_image.max' => 'L\'image de couverture ne peut pas dépasser 2MB.',
            
            'publication_date.before_or_equal' => 'La date de publication ne peut pas être dans le futur.',
            'publication_date.after' => 'La date de publication doit être après 1900.',
            
            'fichier_livre.file' => 'Le fichier doit être un fichier valide.',
            'fichier_livre.mimes' => 'Le fichier doit être au format PDF, EPUB, MOBI, TXT, DOC ou DOCX.',
            'fichier_livre.max' => 'Le fichier ne peut pas dépasser 10MB.',
            
            'format.in' => 'Le format doit être PDF, EPUB, MOBI, TXT, DOC ou DOCX.',
            
            'visibilite.required' => 'La visibilité est obligatoire.',
            'visibilite.in' => 'La visibilité doit être publique ou privée.',
            
            'user_description.max' => 'La description utilisateur ne peut pas dépasser 1000 caractères.',
            
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            
            'langue.in' => 'La langue doit être une langue supportée.',
            
            'nb_pages.integer' => 'Le nombre de pages doit être un nombre entier.',
            'nb_pages.min' => 'Le nombre de pages doit être d\'au moins 1.',
            'nb_pages.max' => 'Le nombre de pages ne peut pas dépasser 10000.',
            
            'resume.max' => 'Le résumé ne peut pas dépasser 5000 caractères.',
            
            'etat.in' => 'L\'état doit être neuf, bon, moyen ou mauvais.',
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
            'title' => 'titre',
            'author' => 'auteur',
            'isbn' => 'ISBN',
            'description' => 'description',
            'cover_image' => 'image de couverture',
            'publication_date' => 'date de publication',
            'genre' => 'genre',
            'fichier_livre' => 'fichier du livre',
            'format' => 'format',
            'visibilite' => 'visibilité',
            'user_description' => 'description utilisateur',
            'categorie_id' => 'catégorie',
            'langue' => 'langue',
            'nb_pages' => 'nombre de pages',
            'resume' => 'résumé',
            'etat' => 'état',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim($this->title),
            'author' => trim($this->author),
            'description' => $this->description ? trim($this->description) : null,
            'user_description' => $this->user_description ? trim($this->user_description) : null,
            'resume' => $this->resume ? trim($this->resume) : null,
            'format' => $this->format ? strtoupper($this->format) : null,
        ]);
    }
}