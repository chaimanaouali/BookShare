@extends('layouts/contentNavbarLayout')

@section('title', 'User Management - BookShare')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1">User Management</h4>
          <p class="text-muted">Manage users and their permissions</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
          <i class="bx bx-plus me-1"></i> Add User
        </a>
      </div>
    </div>
  </div>

  <!-- Users Table -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">All Users</h5>
    </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bx bx-check-circle me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bx bx-error-circle me-2"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>User</th>
              <th>Email</th>
              <th>Role</th>
              <th>Libraries</th>
              <th>Books</th>
              <th>Joined</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-3">
                    <div class="avatar-initial bg-primary rounded">
                      <i class="bx bx-user"></i>
                    </div>
                  </div>
                  <div>
                    <h6 class="mb-0">{{ $user->name }}</h6>
                    @if($user->phone)
                      <small class="text-muted">{{ $user->phone }}</small>
                    @endif
                  </div>
                </div>
              </td>
              <td>{{ $user->email }}</td>
              <td>
                @if($user->role === 'admin')
                  <span class="badge bg-danger">Admin</span>
                @elseif($user->role === 'contributor')
                  <span class="badge bg-warning">Contributor</span>
                @else
                  <span class="badge bg-info">User</span>
                @endif
              </td>
              <td><span class="badge bg-label-primary">{{ $user->bibliotheques_count }}</span></td>
              <td><span class="badge bg-label-success">{{ $user->livres_count }}</span></td>
              <td>{{ $user->created_at->diffForHumans() }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                      <i class="bx bx-show me-1"></i> View
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                      <i class="bx bx-edit me-1"></i> Edit
                    </a>
                    @if($user->id !== auth()->id())
                      <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">
                          <i class="bx bx-trash me-1"></i> Delete
                        </button>
                      </form>
                    @endif
                  </div>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-4">
                <i class="bx bx-user display-4 text-muted mb-3"></i>
                <h5 class="text-muted">No users found</h5>
                <p class="text-muted">Start by adding your first user</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($users->hasPages())
        <div class="d-flex justify-content-center mt-4">
          {{ $users->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
