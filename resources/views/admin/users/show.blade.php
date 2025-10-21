@extends('layouts/contentNavbarLayout')

@section('title', 'User Details - BookShare')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1">User Details</h4>
          <p class="text-muted">View user information and activity</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
            <i class="bx bx-edit me-1"></i> Edit User
          </a>
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back to Users
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- User Information -->
    <div class="col-lg-4">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">User Information</h5>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center mb-4">
            <div class="avatar avatar-xl me-3">
              <div class="avatar-initial bg-primary rounded">
                <i class="bx bx-user"></i>
              </div>
            </div>
            <div>
              <h5 class="mb-1">{{ $user->name }}</h5>
              <p class="text-muted mb-0">{{ $user->email }}</p>
            </div>
          </div>

          <div class="mb-3">
            <strong>Role:</strong>
            @if($user->role === 'admin')
              <span class="badge bg-danger ms-2">Admin</span>
            @elseif($user->role === 'contributor')
              <span class="badge bg-warning ms-2">Contributor</span>
            @else
              <span class="badge bg-info ms-2">User</span>
            @endif
          </div>


          <div class="mb-3">
            <strong>Member Since:</strong><br>
            <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
          </div>

          <div class="mb-0">
            <strong>Last Updated:</strong><br>
            <span class="text-muted">{{ $user->updated_at->diffForHumans() }}</span>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Statistics</h5>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-6 mb-3">
              <div class="border-end">
                <h4 class="mb-1 text-primary">{{ $user->bibliotheques_count }}</h4>
                <small class="text-muted">Libraries</small>
              </div>
            </div>
            <div class="col-6 mb-3">
              <h4 class="mb-1 text-success">{{ $user->livres_count }}</h4>
              <small class="text-muted">Books</small>
            </div>
          </div>
          <div class="row text-center">
            <div class="col-6">
              <div class="border-end">
                <h4 class="mb-1 text-warning">{{ $user->avis_count ?? 0 }}</h4>
                <small class="text-muted">Reviews</small>
              </div>
            </div>
            <div class="col-6">
              <h4 class="mb-1 text-info">{{ $user->emprunts_count ?? 0 }}</h4>
              <small class="text-muted">Borrowings</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- User Activity -->
    <div class="col-lg-8">
      <!-- Libraries -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">User Libraries ({{ $user->bibliotheques->count() }})</h5>
        </div>
        <div class="card-body">
          @if($user->bibliotheques->count() > 0)
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Library Name</th>
                    <th>Books</th>
                    <th>Created</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($user->bibliotheques->take(5) as $bibliotheque)
                  <tr>
                    <td>{{ $bibliotheque->nom_bibliotheque }}</td>
                    <td><span class="badge bg-label-primary">{{ $bibliotheque->nb_livres }}</span></td>
                    <td>{{ $bibliotheque->created_at->diffForHumans() }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @if($user->bibliotheques->count() > 5)
              <div class="text-center mt-3">
                <small class="text-muted">Showing 5 of {{ $user->bibliotheques->count() }} libraries</small>
              </div>
            @endif
          @else
            <div class="text-center py-4">
              <i class="bx bx-library display-4 text-muted mb-3"></i>
              <h6 class="text-muted">No libraries created</h6>
            </div>
          @endif
        </div>
      </div>

      <!-- Recent Books -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Recent Books ({{ $user->livres->count() }})</h5>
        </div>
        <div class="card-body">
          @if($user->livres->count() > 0)
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Visibility</th>
                    <th>Uploaded</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($user->livres->take(5) as $livre)
                  <tr>
                    <td>{{ $livre->title }}</td>
                    <td>{{ $livre->author }}</td>
                    <td>
                      @if($livre->visibilite === 'public')
                        <span class="badge bg-label-success">Public</span>
                      @else
                        <span class="badge bg-label-warning">Private</span>
                      @endif
                    </td>
                    <td>{{ $livre->created_at->diffForHumans() }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @if($user->livres->count() > 5)
              <div class="text-center mt-3">
                <small class="text-muted">Showing 5 of {{ $user->livres->count() }} books</small>
              </div>
            @endif
          @else
            <div class="text-center py-4">
              <i class="bx bx-book display-4 text-muted mb-3"></i>
              <h6 class="text-muted">No books uploaded</h6>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
