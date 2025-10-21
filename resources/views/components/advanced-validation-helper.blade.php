@props(['formId' => 'form', 'showServerErrors' => true])

@if ($showServerErrors && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <h6 class="alert-heading">
            <i class="bx bx-error-circle me-1"></i> Erreurs de validation
        </h6>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Client-side validation helper --}}
<div id="client-validation-helper-{{ $formId }}" class="alert alert-danger" style="display: none;" role="alert">
    <h6 class="alert-heading">
        <i class="bx bx-error-circle me-1"></i> Erreurs de validation
    </h6>
    <ul class="mb-0" id="client-validation-errors-{{ $formId }}"></ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('{{ $formId }}');
    const helper = document.getElementById('client-validation-helper-{{ $formId }}');
    const errorsList = document.getElementById('client-validation-errors-{{ $formId }}');
    
    if (form && helper) {
        // Show client-side validation errors
        window.showClientValidationErrors = function(errors) {
            errorsList.innerHTML = '';
            Object.keys(errors).forEach(field => {
                errors[field].forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = `${field}: ${error}`;
                    errorsList.appendChild(li);
                });
            });
            helper.style.display = 'block';
            helper.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };
        
        // Hide client-side validation errors
        window.hideClientValidationErrors = function() {
            helper.style.display = 'none';
        };
        
        // Clear errors when user starts typing
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function() {
                hideClientValidationErrors();
            });
        });
    }
});
</script>
