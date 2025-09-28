@extends('layouts/contentNavbarLayout')

@section('title', $bibliotheque->nom_bibliotheque . ' - BookShare')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">{{ $bibliotheque->nom_bibliotheque }}</h4>
        <p class="text-muted">{{ $bibliotheque->nb_livres }} books • Created {{ $bibliotheque->created_at->diffForHumans() }}</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('contributor.bibliotheques.edit', $bibliotheque->id) }}" class="btn btn-outline-primary">
          <i class="bx bx-edit me-1"></i> Edit Library
        </a>
        <a href="{{ route('contributor.livres.create', ['bibliotheque' => $bibliotheque->id]) }}" class="btn btn-primary">
          <i class="bx bx-plus me-1"></i> Add Book
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Library Stats -->
<div class="row mb-4">
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avatar">
            <div class="avatar-initial bg-primary rounded">
              <i class="bx bx-book"></i>
            </div>
          </div>
          <div class="ms-3">
            <div class="small text-muted">Total Books</div>
            <div class="h5 mb-0">{{ $bibliotheque->nb_livres }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avatar">
            <div class="avatar-initial bg-success rounded">
              <i class="bx bx-show"></i>
            </div>
          </div>
          <div class="ms-3">
            <div class="small text-muted">Public Books</div>
            <div class="h5 mb-0">{{ $livres->where('visibilite', 'public')->count() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avatar">
            <div class="avatar-initial bg-warning rounded">
              <i class="bx bx-lock"></i>
            </div>
          </div>
          <div class="ms-3">
            <div class="small text-muted">Private Books</div>
            <div class="h5 mb-0">{{ $livres->where('visibilite', 'private')->count() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avatar">
            <div class="avatar-initial bg-info rounded">
              <i class="bx bx-calendar"></i>
            </div>
          </div>
          <div class="ms-3">
            <div class="small text-muted">Last Updated</div>
            <div class="h6 mb-0">{{ $bibliotheque->updated_at->diffForHumans() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Books Table -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Books in this Library</h5>
        <div class="d-flex gap-2">
          <select class="form-select form-select-sm" style="width: auto;" onchange="filterBooks(this.value)">
            <option value="">All Books</option>
            <option value="public">Public Only</option>
            <option value="private">Private Only</option>
          </select>
          <a href="{{ route('contributor.livres.create', ['bibliotheque' => $bibliotheque->id]) }}" class="btn btn-sm btn-primary">
            <i class="bx bx-plus me-1"></i> Add Book
          </a>
        </div>
      </div>
      <div class="card-body">
        @if($livres->count() > 0)
          <div class="table-responsive">
            <table class="table" id="booksTable">
              <thead>
                <tr>
                  <th>Book</th>
                  <th>Author</th>
                  <th>Format</th>
                  <th>Size</th>
                  <th>Visibility</th>
                  <th>Uploaded</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($livres as $livre)
                  <tr data-visibility="{{ $livre->visibilite }}">
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                          <div class="avatar-initial bg-primary rounded">
                            <i class="bx bx-book"></i>
                          </div>
                        </div>
                        <div>
                          <h6 class="mb-0">{{ $livre->livre->title }}</h6>
                          @if($livre->description)
                            <small class="text-muted">{{ Str::limit($livre->description, 50) }}</small>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td>{{ $livre->livre->auteur }}</td>
                    <td>
                      <span class="badge bg-label-info">{{ strtoupper($livre->format) }}</span>
                    </td>
                    <td>{{ $livre->taille }}</td>
                    <td>
                      @if($livre->visibilite === 'public')
                        <span class="badge bg-label-success">Public</span>
                      @else
                        <span class="badge bg-label-warning">Private</span>
                      @endif
                    </td>
                    <td>{{ $livre->created_at->diffForHumans() }}</td>
                    <td>
                      <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('contributor.livres.show', $livre->id) }}">
                            <i class="bx bx-show me-1"></i> View Details
                          </a>
                          <a class="dropdown-item" href="{{ route('contributor.livres.edit', $livre->id) }}">
                            <i class="bx bx-edit me-1"></i> Edit
                          </a>
                          <a class="dropdown-item" href="{{ Storage::url($livre->fichier_livre) }}" target="_blank">
                            <i class="bx bx-download me-1"></i> Download
                          </a>
                          <a class="dropdown-item text-danger" href="#" onclick="deleteLivre({{ $livre->id }})">
                            <i class="bx bx-trash me-1"></i> Delete
                          </a>
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <i class="bx bx-book display-1 text-muted mb-3"></i>
            <h5 class="text-muted">No books in this library yet</h5>
            <p class="text-muted mb-4">Upload your first book to start building your collection</p>
            <a href="{{ route('contributor.livres.create', ['bibliotheque' => $bibliotheque->id]) }}" class="btn btn-primary">
              <i class="bx bx-upload me-1"></i> Upload First Book
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
function filterBooks(visibility) {
  const rows = document.querySelectorAll('#booksTable tbody tr');
  rows.forEach(row => {
    if (visibility === '' || row.dataset.visibility === visibility) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

function deleteLivre(id) {
  if (confirm('Are you sure you want to delete this book?')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/contributor/livres/${id}`;
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    
    const tokenField = document.createElement('input');
    tokenField.type = 'hidden';
    tokenField.name = '_token';
    tokenField.value = '{{ csrf_token() }}';
    
    form.appendChild(methodField);
    form.appendChild(tokenField);
    document.body.appendChild(form);
    form.submit();
  }
}
</script>
@endsection
