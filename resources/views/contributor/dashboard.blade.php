@extends('layouts/contentNavbarLayout')

@section('title', 'Contributor Dashboard - BookShare')

@section('content')
<!-- Dashboard Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="card-title mb-1">Welcome back, {{ auth()->user()->name }}!</h4>
            <p class="text-muted">Manage your virtual libraries and share your digital books</p>
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('contributor.bibliotheques.create') }}" class="btn btn-primary">
              <i class="bx bx-plus me-1"></i> New Library
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-primary rounded">
                <i class="bx bx-library"></i>
              </div>
            </div>
            <div class="ms-3">
              <div class="small text-muted">Total Libraries</div>
              <div class="h5 mb-0">{{ $bibliotheques->count() }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-success rounded">
                <i class="bx bx-book"></i>
              </div>
            </div>
            <div class="ms-3">
              <div class="small text-muted">Total Books</div>
              <div class="h5 mb-0">{{ $totalBooks }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-warning rounded">
                <i class="bx bx-show"></i>
              </div>
            </div>
            <div class="ms-3">
              <div class="small text-muted">Public Books</div>
              <div class="h5 mb-0">{{ $publicBooks }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-info rounded">
                <i class="bx bx-download"></i>
              </div>
            </div>
            <div class="ms-3">
              <div class="small text-muted">Total Downloads</div>
              <div class="h5 mb-0">{{ $totalDownloads ?? 0 }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Libraries -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">My Libraries</h5>
        <a href="{{ route('contributor.bibliotheques.index') }}" class="btn btn-sm btn-outline-primary">
          View All
        </a>
      </div>
      <div class="card-body">
        @if($bibliotheques->count() > 0)
          <div class="row">
            @foreach($bibliotheques->take(3) as $bibliotheque)
              <div class="col-md-4 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <h6 class="card-title">{{ $bibliotheque->nom_bibliotheque }}</h6>
                      <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('contributor.bibliotheques.show', $bibliotheque->id) }}">
                            <i class="bx bx-show me-1"></i> View
                          </a>
                          <a class="dropdown-item" href="{{ route('contributor.bibliotheques.edit', $bibliotheque->id) }}">
                            <i class="bx bx-edit me-1"></i> Edit
                          </a>
                          <a class="dropdown-item text-danger" href="#" onclick="deleteBibliotheque({{ $bibliotheque->id }})">
                            <i class="bx bx-trash me-1"></i> Delete
                          </a>
                        </div>
                      </div>
                    </div>
                    <p class="card-text text-muted small">
                      <i class="bx bx-book me-1"></i> {{ $bibliotheque->nb_livres }} books
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                      <small class="text-muted">{{ $bibliotheque->created_at->diffForHumans() }}</small>
                      <a href="{{ route('contributor.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-sm btn-outline-primary">
                        Manage
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-4">
            <i class="bx bx-library display-4 text-muted mb-3"></i>
            <h5 class="text-muted">No libraries yet</h5>
            <p class="text-muted">Create your first virtual library to start sharing books</p>
            <a href="{{ route('contributor.bibliotheques.create') }}" class="btn btn-primary">
              <i class="bx bx-plus me-1"></i> Create Library
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Recent Books -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Recent Books</h5>
        <a href="{{ route('contributor.livres.index') }}" class="btn btn-sm btn-outline-primary">
          View All
        </a>
      </div>
      <div class="card-body">
        @if($recentBooks->count() > 0)
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Book</th>
                  <th>Library</th>
                  <th>Format</th>
                  <th>Visibility</th>
                  <th>Uploaded</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentBooks->take(5) as $livre)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                          <div class="avatar-initial bg-primary rounded">
                            <i class="bx bx-book"></i>
                          </div>
                        </div>
                        <div>
                          <h6 class="mb-0">{{ $livre->title }}</h6>
                          <small class="text-muted">{{ $livre->author }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ $livre->bibliotheque->nom_bibliotheque }}</td>
                    <td>
                      <span class="badge bg-label-info">{{ strtoupper($livre->format) }}</span>
                    </td>
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
                            <i class="bx bx-show me-1"></i> View
                          </a>
                          <a class="dropdown-item" href="{{ route('contributor.livres.edit', $livre->id) }}">
                            <i class="bx bx-edit me-1"></i> Edit
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
          <div class="text-center py-4">
            <i class="bx bx-book display-4 text-muted mb-3"></i>
            <h5 class="text-muted">No books uploaded yet</h5>
            <p class="text-muted">Upload your first digital book to start sharing</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
function deleteBibliotheque(id) {
  if (confirm('Are you sure you want to delete this library? This will also delete all books in it.')) {
    // Implement delete functionality
    console.log('Delete bibliotheque:', id);
  }
}

function deleteLivre(id) {
  if (confirm('Are you sure you want to delete this book?')) {
    // Implement delete functionality
    console.log('Delete livre:', id);
  }
}
</script>
@endsection
