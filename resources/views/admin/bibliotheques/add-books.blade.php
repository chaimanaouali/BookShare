@extends('layouts/contentNavbarLayout')

@section('title', 'Add Books to Library - Admin')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">Add Books to "{{ $bibliotheque->nom_bibliotheque }}"</h4>
        <p class="text-muted">Select books from the system to add to this library</p>
      </div>
      <a href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Library
      </a>
    </div>
  </div>
</div>

<!-- Book Selection Form -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Available Books</h5>
        <div>
          <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
        </div>
      </div>
      <div class="card-body">
        @if($availableBooks->count() > 0)
          <form action="{{ route('admin.bibliotheques.store-books', $bibliotheque->id) }}" method="POST" id="bookSelectionForm">
            @csrf
            
            <div class="row">
              @foreach($availableBooks as $book)
                <div class="col-md-6 col-lg-4 mb-3">
                  <div class="card h-100 book-selection-card">
                    <div class="card-body">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="selected_books[]" 
                               value="{{ $book->id }}" 
                               id="book_{{ $book->id }}">
                        <label class="form-check-label w-100" for="book_{{ $book->id }}">
                          <div class="d-flex align-items-start">
                            <div class="avatar avatar-sm me-3">
                              <div class="avatar-initial bg-primary rounded">
                                <i class="bx bx-book"></i>
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="mb-1">{{ $book->title ?? 'Untitled' }}</h6>
                              <p class="text-muted small mb-1">{{ $book->author ?? 'Unknown Author' }}</p>
                              <p class="text-muted small mb-1">Owner: {{ $book->user->name ?? 'Unknown' }}</p>
                              @if($book->format)
                                <span class="badge bg-label-info">{{ strtoupper($book->format) }}</span>
                              @endif
                              @if($book->visibilite)
                                <span class="badge bg-label-{{ $book->visibilite == 'public' ? 'success' : 'warning' }} ms-1">
                                  {{ ucfirst($book->visibilite) }}
                                </span>
                              @endif
                            </div>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
              <div>
                <span class="text-muted">Selected: <span id="selectedCount">0</span> books</span>
              </div>
              <div>
                <button type="button" class="btn btn-outline-secondary me-2" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="addBooksBtn" disabled>
                  <i class="bx bx-plus me-1"></i> Add Selected Books
                </button>
              </div>
            </div>
          </form>
        @else
          <div class="text-center py-5">
            <i class="bx bx-book display-1 text-muted mb-3"></i>
            <h5 class="text-muted">No Available Books</h5>
            <p class="text-muted">All books are already in this library.</p>
            <div class="mt-4">
              <a href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Library
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_books[]"]');
    const selectedCount = document.getElementById('selectedCount');
    const addBooksBtn = document.getElementById('addBooksBtn');

    function updateSelection() {
        const checked = document.querySelectorAll('input[name="selected_books[]"]:checked');
        selectedCount.textContent = checked.length;
        addBooksBtn.disabled = checked.length === 0;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    // Initial update
    updateSelection();
});

function selectAll() {
    const checkboxes = document.querySelectorAll('input[name="selected_books[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectedCount').textContent = checkboxes.length;
    document.getElementById('addBooksBtn').disabled = false;
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('input[name="selected_books[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectedCount').textContent = '0';
    document.getElementById('addBooksBtn').disabled = true;
}
</script>

<style>
.book-selection-card {
    transition: all 0.2s ease;
    cursor: pointer;
}

.book-selection-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.book-selection-card .form-check-input:checked + .form-check-label .book-selection-card {
    border-color: var(--bs-primary);
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}
</style>
@endsection
