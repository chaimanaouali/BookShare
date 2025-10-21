@extends('layouts/contentNavbarLayout')

@section('title', 'All Libraries - Admin')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">All Libraries</h4>
        <p class="text-muted">Manage all virtual libraries in the system</p>
      </div>
      <a href="{{ route('admin.bibliotheques.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Create Library
      </a>
    </div>
  </div>
</div>

<!-- Libraries Grid -->
@if($bibliotheques->count() > 0)
  <div class="row">
    @foreach($bibliotheques as $bibliotheque)
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <h5 class="card-title">{{ $bibliotheque->nom_bibliotheque }}</h5>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}">
                    <i class="bx bx-show me-1"></i> View Details
                  </a>
                  <a class="dropdown-item" href="{{ route('admin.bibliotheques.edit', $bibliotheque->id) }}">
                    <i class="bx bx-edit me-1"></i> Edit
                  </a>
                  <a class="dropdown-item text-danger" href="#" onclick="deleteBibliotheque({{ $bibliotheque->id }})">
                    <i class="bx bx-trash me-1"></i> Delete
                  </a>
                </div>
              </div>
            </div>
            
            <div class="row mb-3">
              <div class="col-6">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-2">
                    <div class="avatar-initial bg-primary rounded">
                      <i class="bx bx-book"></i>
                    </div>
                  </div>
                  <div>
                    <div class="h6 mb-0">{{ $bibliotheque->nb_livres }}</div>
                    <small class="text-muted">Books</small>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-2">
                    <div class="avatar-initial bg-info rounded">
                      <i class="bx bx-user"></i>
                    </div>
                  </div>
                  <div>
                    <div class="h6 mb-0">{{ $bibliotheque->user->name ?? 'Unknown' }}</div>
                    <small class="text-muted">Owner</small>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted">Created {{ $bibliotheque->created_at->diffForHumans() }}</small>
              <a href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-right-arrow-alt me-1"></i> Manage
              </a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@else
  <!-- Empty State -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="bx bx-library display-1 text-muted mb-4"></i>
          <h4 class="text-muted mb-3">No libraries found</h4>
          <p class="text-muted mb-4">Create the first virtual library in the system</p>
          <a href="{{ route('admin.bibliotheques.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Create First Library
          </a>
        </div>
      </div>
    </div>
  </div>
@endif
@endsection

@section('page-script')
<script>
function deleteBibliotheque(id) {
  if (confirm('Are you sure you want to delete this library? This will also delete all books in it.')) {
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/bibliotheques/${id}`;
    
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
