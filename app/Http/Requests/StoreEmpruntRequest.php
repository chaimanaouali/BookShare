<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpruntRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can create emprunts
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'utilisateur_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'livre_id' => [
                'required',
                'integer',
                'exists:livres,id'
            ],
            'date_emprunt' => [
                'required',
                'date',
                'before_or_equal:today' // Cannot borrow in the future
            ],
            'date_retour_prev' => [
                'required',
                'date',
                'after:date_emprunt',
                'after_or_equal:today' // Return date should be at least today
            ],
            'date_retour_eff' => [
                'nullable',
                'date',
                'after_or_equal:date_emprunt'
            ],
            'statut' => [
                'required',
                'string',
                'max:50',
                Rule::in(['En cours', 'Retourné', 'En retard'])
            ],
            'penalite' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999.99'
            ],
            'commentaire' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/u'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'utilisateur_id.required' => 'L\'utilisateur est obligatoire.',
            'utilisateur_id.integer' => 'L\'utilisateur doit être un nombre entier.',
            'utilisateur_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
            
            'livre_id.required' => 'Le livre est obligatoire.',
            'livre_id.integer' => 'L\'ID du livre doit être un nombre entier.',
            'livre_id.exists' => 'Le livre sélectionné n\'existe pas.',
            
            'date_emprunt.required' => 'La date d\'emprunt est obligatoire.',
            'date_emprunt.date' => 'La date d\'emprunt doit être une date valide.',
            'date_emprunt.before_or_equal' => 'La date d\'emprunt ne peut pas être dans le futur.',
            
            'date_retour_prev.required' => 'La date de retour prévue est obligatoire.',
            'date_retour_prev.date' => 'La date de retour prévue doit être une date valide.',
            'date_retour_prev.after' => 'La date de retour prévue doit être après la date d\'emprunt.',
            'date_retour_prev.after_or_equal' => 'La date de retour prévue doit être au moins aujourd\'hui.',
            
            'date_retour_eff.date' => 'La date de retour effective doit être une date valide.',
            'date_retour_eff.after_or_equal' => 'La date de retour effective doit être après la date d\'emprunt.',
            
            'statut.required' => 'Le statut est obligatoire.',
            'statut.string' => 'Le statut doit être une chaîne de caractères.',
            'statut.max' => 'Le statut ne peut pas dépasser 50 caractères.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            
            'penalite.numeric' => 'La pénalité doit être un nombre.',
            'penalite.min' => 'La pénalité ne peut pas être négative.',
            'penalite.max' => 'La pénalité ne peut pas dépasser 999.99€.',
            
            'commentaire.string' => 'Le commentaire doit être une chaîne de caractères.',
            'commentaire.max' => 'Le commentaire ne peut pas dépasser 500 caractères.',
            'commentaire.regex' => 'Le commentaire contient des caractères non autorisés.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'utilisateur_id' => 'utilisateur',
            'livre_id' => 'livre',
            'date_emprunt' => 'date d\'emprunt',
            'date_retour_prev' => 'date de retour prévue',
            'date_retour_eff' => 'date de retour effective',
            'statut' => 'statut',
            'penalite' => 'pénalité',
            'commentaire' => 'commentaire',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $dateEmprunt = $this->input('date_emprunt');
            $dateRetourPrev = $this->input('date_retour_prev');
            $dateRetourEff = $this->input('date_retour_eff');
            $statut = $this->input('statut');
            $penalite = $this->input('penalite');
            $commentaire = $this->input('commentaire');
            
            // Validation des dates
            if ($dateEmprunt && $dateRetourPrev) {
                $dateEmpruntObj = \Carbon\Carbon::parse($dateEmprunt);
                $dateRetourPrevObj = \Carbon\Carbon::parse($dateRetourPrev);
                
                // Vérifier que la durée d'emprunt n'est pas trop longue (max 30 jours)
                if ($dateEmpruntObj->diffInDays($dateRetourPrevObj) > 30) {
                    $validator->errors()->add('date_retour_prev', 'La durée d\'emprunt ne peut pas dépasser 30 jours.');
                }
                
                // Vérifier que la durée d'emprunt n'est pas trop courte (min 1 jour)
                if ($dateEmpruntObj->diffInDays($dateRetourPrevObj) < 1) {
                    $validator->errors()->add('date_retour_prev', 'La durée d\'emprunt doit être d\'au moins 1 jour.');
                }
            }
            
            // Validation de la date de retour effective
            if ($dateRetourEff && $dateEmprunt) {
                $dateEmpruntObj = \Carbon\Carbon::parse($dateEmprunt);
                $dateRetourEffObj = \Carbon\Carbon::parse($dateRetourEff);
                
                if ($dateRetourEffObj->lt($dateEmpruntObj)) {
                    $validator->errors()->add('date_retour_eff', 'La date de retour effective ne peut pas être antérieure à la date d\'emprunt.');
                }
            }
            
            // Validation du statut
            if ($statut === 'Retourné' && !$dateRetourEff) {
                $validator->errors()->add('date_retour_eff', 'La date de retour effective est obligatoire quand le statut est "Retourné".');
            }
            
            if ($statut === 'En retard' && $dateRetourPrev) {
                $dateRetourPrevObj = \Carbon\Carbon::parse($dateRetourPrev);
                if ($dateRetourPrevObj->isFuture()) {
                    $validator->errors()->add('statut', 'Le statut ne peut pas être "En retard" si la date de retour prévue est dans le futur.');
                }
            }
            
            // Validation de la pénalité
            if ($penalite !== null && $penalite > 0) {
                if ($statut !== 'En retard' && $statut !== 'Retourné') {
                    $validator->errors()->add('penalite', 'Une pénalité ne peut être appliquée que pour les emprunts en retard ou retournés.');
                }
            }
            
            // Validation du commentaire
            if ($commentaire) {
                // Check for inappropriate content
                $inappropriateWords = ['spam', 'test', 'temp', 'fake', 'hate', 'stupid'];
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
                if (strlen($meaningfulContent) < 3) {
                    $validator->errors()->add('commentaire', 'Le commentaire doit contenir au moins 3 caractères significatifs.');
                }
                
                // Check for excessive use of special characters
                $specialCharCount = preg_match_all('/[!@#$%^&*()_+=\[\]{}|;:,.<>?]/', $commentaire);
                if ($specialCharCount > strlen($commentaire) * 0.3) {
                    $validator->errors()->add('commentaire', 'Le commentaire contient trop de caractères spéciaux.');
                }
                
                // Check for all caps
                if (strlen($commentaire) > 10 && strtoupper($commentaire) === $commentaire) {
                    $validator->errors()->add('commentaire', 'Le commentaire ne peut pas être entièrement en majuscules.');
                }
            }
        });
    }
}