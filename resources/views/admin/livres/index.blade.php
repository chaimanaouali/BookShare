@extends('layouts/contentNavbarLayout')

@section('title', 'All Books - Admin')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">All Books</h4>
        <p class="text-muted">Manage all books in the system</p>
      </div>
      <a href="{{ route('admin.livres.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Create Book
      </a>
    </div>
  </div>
</div>

<!-- Books Table -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Books Management</h5>
      </div>
      <div class="card-body">
        @if($livres->count() > 0)
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Author</th>
                  <th>Owner</th>
                  <th>Library</th>
                  <th>Format</th>
                  <th>Visibility</th>
                  <th>Uploaded</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($livres as $livre)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-3">
                        <div class="avatar-initial bg-primary rounded">
                          <i class="bx bx-book"></i>
                        </div>
                      </div>
                      <div>
                        <h6 class="mb-0">{{ $livre->title ?? '-' }}</h6>
                        @if($livre->description)
                          <small class="text-muted">{{ Str::limit($livre->description, 50) }}</small>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td>{{ $livre->author ?? '-' }}</td>
                  <td>{{ $livre->user->name ?? 'Unknown' }}</td>
                  <td>{{ $livre->bibliotheque->nom_bibliotheque ?? 'No Library' }}</td>
                  <td>
                    <span class="badge bg-label-info">{{ strtoupper($livre->format ?? '-') }}</span>
                  </td>
                  <td>
                    <span class="badge bg-label-{{ $livre->visibilite == 'public' ? 'success' : 'warning' }}">{{ ucfirst($livre->visibilite) }}</span>
                  </td>
                  <td>{{ $livre->created_at->format('Y-m-d H:i') }}</td>
                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.livres.show', $livre->id) }}">
                          <i class="bx bx-show me-1"></i> View Details
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.livres.edit', $livre->id) }}">
                          <i class="bx bx-edit me-1"></i> Edit
                        </a>
                        @if($livre->fichier_livre)
                          <a class="dropdown-item" href="{{ Storage::url($livre->fichier_livre) }}" target="_blank">
                            <i class="bx bx-download me-1"></i> Download
                          </a>
                        @endif
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
            <h5 class="text-muted">No books found</h5>
            <p class="text-muted">Create the first book in the system</p>
            <a href="{{ route('admin.livres.create') }}" class="btn btn-primary">
              <i class="bx bx-plus me-1"></i> Create First Book
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
function deleteLivre(id) {
  if (confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/livres/${id}`;
    
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
