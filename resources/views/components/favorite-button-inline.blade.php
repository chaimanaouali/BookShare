@props(['livre', 'showCount' => false, 'size' => 'sm'])

@php
    $isFavorited = auth()->check() ? $livre->isFavoritedBy(auth()->id()) : false;
    $favorisCount = $livre->favoris_count;
@endphp

<div class="favorite-component d-inline-block" data-book-id="{{ $livre->id }}">
    <button class="btn btn-{{ $size }} {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} favorite-toggle-btn" 
            data-book-id="{{ $livre->id }}"
            onclick="toggleFavoriteComponent({{ $livre->id }})"
            title="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
        <i class="bx {{ $isFavorited ? 'bx-heart-fill' : 'bx-heart' }}"></i>
        @if($showCount && $favorisCount > 0)
            <span class="ms-1">{{ $favorisCount }}</span>
        @endif
    </button>
</div>

<script>
// Toggle favorite for component
function toggleFavoriteComponent(bookId) {
    const button = document.querySelector(`[data-book-id="${bookId}"] .favorite-toggle-btn`);
    const icon = button.querySelector('i');
    const countSpan = button.querySelector('span');
    
    // Show loading state
    button.disabled = true;
    icon.className = 'bx bx-loader-alt bx-spin';
    
    fetch(`/favoris/toggle/${bookId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        
        if (data.success) {
            // Update button appearance
            if (data.is_favorited) {
                button.classList.remove('btn-outline-danger');
                button.classList.add('btn-danger');
                icon.className = 'bx bx-heart-fill';
                button.title = 'Retirer des favoris';
            } else {
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-danger');
                icon.className = 'bx bx-heart';
                button.title = 'Ajouter aux favoris';
            }
            
            // Update count if shown
            if (countSpan && data.favoris_count !== undefined) {
                countSpan.textContent = data.favoris_count;
            }
            
            // Show success message
            showToast(data.message, 'success');
        } else {
            // Revert icon on error
            icon.className = data.is_favorited ? 'bx bx-heart-fill' : 'bx bx-heart';
            showToast(data.error || 'Une erreur est survenue', 'danger');
        }
    })
    .catch(error => {
        button.disabled = false;
        // Revert to original state on error
        icon.className = 'bx bx-heart';
        console.error('Error:', error);
        showToast('Une erreur est survenue', 'danger');
    });
}

// Show toast notification
function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bx bx-${type === 'success' ? 'check-circle' : 'error-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Add to toast container or create one
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.appendChild(toast);
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}
</script>

<style>
.favorite-toggle-btn {
    transition: all 0.2s ease-in-out;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dc3545;
}

.favorite-toggle-btn:hover {
    transform: scale(1.1);
}

.favorite-toggle-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.favorite-toggle-btn.btn-sm {
    width: 28px;
    height: 28px;
    font-size: 0.875rem;
}

.favorite-toggle-btn.btn-lg {
    width: 40px;
    height: 40px;
    font-size: 1.25rem;
}
</style>
