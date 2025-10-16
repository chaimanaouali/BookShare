@extends('layouts/contentNavbarLayout')

@section('title', 'Category: ' . $category->nom)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <!-- Category Header -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="bx bx-category me-2"></i>{{ $category->nom }}
                        </h5>
                        <small class="text-muted">Category Details</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-arrow-back me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            @if($category->description)
                                <p class="mb-3">{{ $category->description }}</p>
                            @else
                                <p class="text-muted mb-3">No description provided</p>
                            @endif
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bx bx-book me-2 text-primary"></i>
                                        <span class="fw-medium">Books in Category:</span>
                                        <span class="badge bg-label-primary ms-2">{{ $category->livres->count() }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bx bx-calendar me-2 text-info"></i>
                                        <span class="fw-medium">Created:</span>
                                        <span class="text-muted ms-2">{{ $category->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bx bx-stats me-1"></i> Category Statistics
                                    </h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                            <h4 class="mb-0 text-primary">{{ $category->livres->count() }}</h4>
                                            <small class="text-muted">Total Books</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="mb-0 text-success">{{ $category->livres->where('visibilite', 'public')->count() }}</h4>
                                            <small class="text-muted">Public Books</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books in Category -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Books in this Category</h5>
                </div>
                <div class="card-body">
                    @if($category->livres->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Author</th>
                                        <th>Owner</th>
                                        <th>Library</th>
                                        <th>Visibility</th>
                                        <th>Added</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->livres as $livre)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initial bg-label-warning rounded">
                                                            <i class="bx bx-book"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $livre->title ?? 'Untitled' }}</h6>
                                                        @if($livre->isbn)
                                                            <small class="text-muted">ISBN: {{ $livre->isbn }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $livre->author ?? 'Unknown' }}</td>
                                            <td>
                                                @if($livre->user)
                                                    <span class="fw-medium">{{ $livre->user->name }}</span>
                                                @else
                                                    <span class="text-muted">No owner</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($livre->bibliotheque)
                                                    <span class="badge bg-label-info">{{ $livre->bibliotheque->nom_bibliotheque }}</span>
                                                @else
                                                    <span class="text-muted">No library</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-label-{{ $livre->visibilite == 'public' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($livre->visibilite) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $livre->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('contributor.livres.show', $livre->id) }}">
                                                            <i class="bx bx-show me-1"></i> View
                                                        </a>
                                                        @if($livre->fichier_livre)
                                                            <a class="dropdown-item" href="{{ Storage::url($livre->fichier_livre) }}" download>
                                                                <i class="bx bx-download me-1"></i> Download
                                                            </a>
                                                        @endif
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
                            <div class="mb-3">
                                <i class="bx bx-book display-4 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Books in this Category</h5>
                            <p class="text-muted">Books will appear here when they are assigned to this category.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
