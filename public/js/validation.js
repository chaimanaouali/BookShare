/**
 * Client-side validation system for Laravel forms
 */
class FormValidator {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        this.rules = {};
        this.messages = {};
        this.init();
    }

    init() {
        if (!this.form) return;
        
        this.setupValidationRules();
        this.bindEvents();
        this.setupRealTimeValidation();
    }

    setupValidationRules() {
        // Common validation rules
        this.rules = {
            required: (value) => value.trim() !== '',
            minLength: (value, min) => value.length >= min,
            maxLength: (value, max) => value.length <= max,
            email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            numeric: (value) => /^\d+$/.test(value),
            alphanumeric: (value) => /^[a-zA-Z0-9\s\-_\.]+$/.test(value),
            isbn: (value) => this.validateISBN(value),
            fileSize: (file, maxSizeMB) => {
                if (!file) return true;
                return file.size <= (maxSizeMB * 1024 * 1024);
            },
            fileType: (file, allowedTypes) => {
                if (!file) return true;
                return allowedTypes.includes(file.type);
            }
        };

        // Error messages
        this.messages = {
            required: 'Ce champ est obligatoire.',
            minLength: (min) => `Ce champ doit contenir au moins ${min} caractères.`,
            maxLength: (max) => `Ce champ ne peut pas dépasser ${max} caractères.`,
            email: 'Veuillez entrer une adresse email valide.',
            numeric: 'Ce champ doit contenir uniquement des chiffres.',
            alphanumeric: 'Ce champ contient des caractères non autorisés.',
            isbn: 'L\'ISBN doit être un ISBN-10 ou ISBN-13 valide.',
            fileSize: (maxSize) => `Le fichier ne peut pas dépasser ${maxSize}MB.`,
            fileType: (types) => `Le fichier doit être de type: ${types.join(', ')}.`
        };
    }

    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                this.showFormErrors();
            }
        });

        // Real-time validation on blur
        this.form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });
        });
    }

    setupRealTimeValidation() {
        // Add visual feedback for validation states
        this.form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', () => {
                this.clearFieldError(field);
            });
        });
    }

    validateForm() {
        let isValid = true;
        const fields = this.form.querySelectorAll('input[required], textarea[required], select[required]');
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = '';

        // Required validation
        if (field.hasAttribute('required') && !this.rules.required(value)) {
            isValid = false;
            errorMessage = this.messages.required;
        }

        // Min length validation
        if (isValid && field.dataset.minLength && !this.rules.minLength(value, parseInt(field.dataset.minLength))) {
            isValid = false;
            errorMessage = this.messages.minLength(field.dataset.minLength);
        }

        // Max length validation
        if (isValid && field.dataset.maxLength && !this.rules.maxLength(value, parseInt(field.dataset.maxLength))) {
            isValid = false;
            errorMessage = this.messages.maxLength(field.dataset.maxLength);
        }

        // Email validation
        if (isValid && field.type === 'email' && value && !this.rules.email(value)) {
            isValid = false;
            errorMessage = this.messages.email;
        }

        // ISBN validation
        if (isValid && field.dataset.validate === 'isbn' && value && !this.rules.isbn(value)) {
            isValid = false;
            errorMessage = this.messages.isbn;
        }

        // File validation
        if (isValid && field.type === 'file' && field.files.length > 0) {
            const file = field.files[0];
            
            if (field.dataset.maxSize && !this.rules.fileSize(file, parseInt(field.dataset.maxSize))) {
                isValid = false;
                errorMessage = this.messages.fileSize(field.dataset.maxSize);
            }

            if (field.dataset.allowedTypes && !this.rules.fileType(file, field.dataset.allowedTypes.split(','))) {
                isValid = false;
                errorMessage = this.messages.fileType(field.dataset.allowedTypes.split(','));
            }
        }

        // Show/hide error
        if (!isValid) {
            this.showFieldError(field, errorMessage);
        } else {
            this.clearFieldError(field);
        }

        return isValid;
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    showFormErrors() {
        // Scroll to first error
        const firstError = this.form.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }

    validateISBN(isbn) {
        if (!isbn) return true;
        
        // Remove hyphens and spaces
        const cleanISBN = isbn.replace(/[-\s]/g, '');
        
        // Check ISBN-10
        if (cleanISBN.length === 10) {
            return this.validateISBN10(cleanISBN);
        }
        
        // Check ISBN-13
        if (cleanISBN.length === 13) {
            return this.validateISBN13(cleanISBN);
        }
        
        return false;
    }

    validateISBN10(isbn) {
        let sum = 0;
        for (let i = 0; i < 9; i++) {
            if (!/\d/.test(isbn[i])) return false;
            sum += parseInt(isbn[i]) * (10 - i);
        }
        
        const checkDigit = isbn[9] === 'X' || isbn[9] === 'x' ? 10 : parseInt(isbn[9]);
        return (sum + checkDigit) % 11 === 0;
    }

    validateISBN13(isbn) {
        if (!/^\d{13}$/.test(isbn)) return false;
        
        let sum = 0;
        for (let i = 0; i < 12; i++) {
            sum += parseInt(isbn[i]) * (i % 2 === 0 ? 1 : 3);
        }
        
        const checkDigit = (10 - (sum % 10)) % 10;
        return checkDigit === parseInt(isbn[12]);
    }
}

// Auto-initialize validation for forms with data-validate attribute
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[data-validate]').forEach(form => {
        new FormValidator(`#${form.id}`);
    });
});

// Export for manual initialization
window.FormValidator = FormValidator;
