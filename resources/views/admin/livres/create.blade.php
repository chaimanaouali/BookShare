@extends('layouts/contentNavbarLayout')

@section('title', 'Upload Book - Admin')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">Upload New Book</h4>
        <p class="text-muted">Add a new digital book to the system</p>
      </div>
      <a href="{{ route('admin.livres.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back
      </a>
    </div>
  </div>
</div>

<!-- Upload Form -->
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Book Information</h5>
      </div>
      <div class="card-body">
        <!-- Plagiarism Alert -->
        @error('plagiarism')
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @enderror

        <form action="{{ route('admin.livres.store') }}" method="POST" enctype="multipart/form-data" novalidate>
          @csrf

          <!-- Book Selection -->
          <div class="mb-4">
            <label class="form-label">Select Book <span class="text-danger">*</span></label>
            <div class="row">
              <div class="col-md-6">
                <div class="card border-2 border-dashed" id="dropZone">
                  <div class="card-body text-center py-4">
                    <i class="bx bx-cloud-upload display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">Drop your book file here</h5>
                    <p class="text-muted small">or click to browse</p>
                    <input type="file" class="form-control @error('fichier_livre') is-invalid @enderror"
                           id="fichier_livre" name="fichier_livre"
                           accept=".pdf,.epub,.mobi,.txt" required>
                    @error('fichier_livre')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="small text-muted">
                  <h6>Supported Formats:</h6>
                  <ul class="list-unstyled">
                    <li><i class="bx bx-check text-success me-1"></i> PDF (.pdf)</li>
                    <li><i class="bx bx-check text-success me-1"></i> EPUB (.epub)</li>
                    <li><i class="bx bx-check text-success me-1"></i> MOBI (.mobi)</li>
                    <li><i class="bx bx-check text-success me-1"></i> Text (.txt)</li>
                  </ul>
                  <p class="text-warning small">
                    <i class="bx bx-info-circle me-1"></i> Maximum file size: 10MB
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Book Details -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="bibliotheque_id" class="form-label">Library <span class="text-danger">*</span></label>
              <select class="form-select @error('bibliotheque_id') is-invalid @enderror" id="bibliotheque_id" name="bibliotheque_id" required>
                <option value="">Select a library</option>
                @foreach(\App\Models\BibliothequeVirtuelle::all() as $bibliotheque)
                  <option value="{{ $bibliotheque->id }}" {{ old('bibliotheque_id') == $bibliotheque->id ? 'selected' : '' }}>
                    {{ $bibliotheque->nom }} ({{ $bibliotheque->user->name }})
                  </option>
                @endforeach
              </select>
              @error('bibliotheque_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Challenge Selection -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="defi_id" class="form-label">Associate with Challenge (Optional)</label>
              <select class="form-select @error('defi_id') is-invalid @enderror" id="defi_id" name="defi_id">
                <option value="">No challenge</option>
                @foreach(\App\Models\Defi::all() as $defi)
                  <option value="{{ $defi->id }}" {{ old('defi_id') == $defi->id ? 'selected' : '' }}>
                    {{ $defi->titre }}
                  </option>
                @endforeach
              </select>
              @error('defi_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">
                <small class="text-muted">Associate this book with a reading challenge</small>
              </div>
            </div>
          </div>

          <!-- New Book Details -->
          <div id="newBookFields" class="row mb-3">
            <div class="col-md-6">
              <label for="title" class="form-label">Book Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title" value="{{ old('title') }}"
                     placeholder="Enter book title" required>
              @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('author') is-invalid @enderror"
                     id="author" name="author" value="{{ old('author') }}"
                     placeholder="Enter author name" required>
              @error('author')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="isbn" class="form-label">ISBN</label>
              <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                     id="isbn" name="isbn" value="{{ old('isbn') }}"
                     placeholder="Enter ISBN (optional)">
              @error('isbn')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="categorie_id" class="form-label">Category</label>
              <select class="form-select @error('categorie_id') is-invalid @enderror"
                      id="categorie_id" name="categorie_id">
                <option value="">Select a category (optional)</option>
                @foreach(\App\Models\Categorie::orderBy('nom')->get() as $categorie)
                  <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->nom }}
                  </option>
                @endforeach
              </select>
              @error('categorie_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Visibility and Description -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="visibilite" class="form-label">Visibility <span class="text-danger">*</span></label>
              <select class="form-select @error('visibilite') is-invalid @enderror" id="visibilite" name="visibilite" required>
                <option value="private" {{ old('visibilite', 'private') == 'private' ? 'selected' : '' }}>Private</option>
                <option value="public" {{ old('visibilite') == 'public' ? 'selected' : '' }}>Public</option>
              </select>
              @error('visibilite')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">
                <small class="text-muted">
                  <i class="bx bx-info-circle me-1"></i>
                  Public books can be discovered by other users
                </small>
              </div>
            </div>
            <div class="col-md-6">
              <label for="format" class="form-label">Format</label>
              <input type="text" class="form-control" id="format" name="format"
                     value="{{ old('format') }}" placeholder="Auto-detected from file">
              <div class="form-text">Leave empty for auto-detection</div>
            </div>
          </div>

          <div class="mb-4">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                      placeholder="Add a description for this book instance (optional)">{{ old('description') }}</textarea>
            <div class="form-text">Optional description for this specific book file</div>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.livres.index') }}" class="btn btn-outline-secondary">
              Cancel
            </a>
            <button type="submit" class="btn btn-primary" onclick="return validateForm()">
              <i class="bx bx-upload me-1"></i> Upload Book
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Help Card -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="bx bx-help-circle me-1"></i> Upload Tips
        </h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <h6 class="text-primary">File Requirements</h6>
          <ul class="small text-muted mb-0">
            <li>Maximum size: 10MB</li>
            <li>Supported formats: PDF, EPUB, MOBI, TXT</li>
            <li>Ensure file is not corrupted</li>
          </ul>
        </div>

        <div class="mb-3">
          <h6 class="text-primary">Book Entry</h6>
          <p class="small text-muted mb-0">Create a book entry first if the book doesn't exist in the system</p>
        </div>

        <div class="mb-0">
          <h6 class="text-primary">Visibility</h6>
          <p class="small text-muted mb-0">Choose public to let others discover your book, or private for personal use only</p>
        </div>
      </div>
    </div>

    <!-- Recent Uploads -->
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="bx bx-history me-1"></i> Recent Uploads
        </h5>
      </div>
      <div class="card-body">
        @php
          $recentUploads = \App\Models\Livre::orderBy('created_at', 'desc')->take(3)->get();
        @endphp
        @if($recentUploads->count() > 0)
          @foreach($recentUploads as $upload)
            <div class="d-flex align-items-center mb-2">
              <div class="avatar avatar-sm me-2">
                <div class="avatar-initial bg-primary rounded">
                  <i class="bx bx-book"></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0 small">{{ $upload->title }}</h6>
                <small class="text-muted">{{ $upload->created_at->diffForHumans() }}</small>
              </div>
            </div>
          @endforeach
        @else
          <p class="small text-muted text-center">No recent uploads</p>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Create Book Modal -->
<div class="modal fade" id="createBookModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create New Book Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- Error display for AJAX -->
      <div id="createBookError" class="alert alert-danger" style="display:none;"></div>
      <form id="createBookForm">
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_title" class="form-label">Book Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="new_title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="new_author" class="form-label">Author <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="new_author" name="author" required>
          </div>
          <div class="mb-3">
            <label for="new_isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="new_isbn" name="isbn">
          </div>
          <div class="mb-3">
            <label for="new_categorie_id" class="form-label">Category</label>
            <select class="form-select" id="new_categorie_id" name="categorie_id">
              <option value="">Select a category</option>
              @foreach(\App\Models\Categorie::orderBy('nom')->get() as $categorie)
                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="new_description" class="form-label">Description</label>
            <textarea class="form-control" id="new_description" name="description" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Create Book</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function validateForm() {
    const fileInput = document.getElementById('fichier_livre');
    const titleInput = document.getElementById('title');
    const authorInput = document.getElementById('author');
    const bibliothequeSelect = document.getElementById('bibliotheque_id');
    
    if (!fileInput.files[0]) {
        alert('Please select a book file.');
        return false;
    }
    
    if (!titleInput.value.trim()) {
        alert('Please enter a book title.');
        return false;
    }
    
    if (!authorInput.value.trim()) {
        alert('Please enter an author name.');
        return false;
    }
    
    if (!bibliothequeSelect.value) {
        alert('Please select a library.');
        return false;
    }
    
    return true;
}

// Drag and drop functionality
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fichier_livre');

dropZone.addEventListener('click', () => {
    fileInput.click();
});

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-primary', 'bg-light');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-light');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-light');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
    }
});

// Create book modal functionality
document.getElementById('createBookForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.livres.create-book") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createBookModal'));
            modal.hide();
            
            // Reset form
            this.reset();
            
            // Show success message
            alert('Book created successfully!');
        } else {
            // Show error
            document.getElementById('createBookError').style.display = 'block';
            document.getElementById('createBookError').textContent = data.message || 'An error occurred';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('createBookError').style.display = 'block';
        document.getElementById('createBookError').textContent = 'An error occurred while creating the book';
    });
});
</script>

<style>
.card.border-2.border-dashed {
    cursor: pointer;
    transition: all 0.3s ease;
}

.card.border-2.border-dashed:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9fa;
}

.card.border-2.border-dashed.border-primary {
    border-color: #0d6efd !important;
    background-color: #e7f3ff;
}
</style>
@endsection