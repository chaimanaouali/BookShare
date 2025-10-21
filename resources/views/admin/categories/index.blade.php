@extends('layouts/contentNavbarLayout')

@section('title', 'Categories Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Categories Management</h5>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Add Category
                    </a>
                </div>

                <!-- Search and Filter Section -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-3">
                        <!-- Search Bar -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Search by name or description...">
                            </div>
                        </div>

                        <!-- Book Count Range -->
                        <div class="col-md-2">
                            <label for="books_min" class="form-label">Min Books</label>
                            <input type="number" class="form-control" id="books_min" name="books_min" 
                                   value="{{ request('books_min') }}" placeholder="Min" min="0">
                        </div>

                        <div class="col-md-2">
                            <label for="books_max" class="form-label">Max Books</label>
                            <input type="number" class="form-control" id="books_max" name="books_max" 
                                   value="{{ request('books_max') }}" placeholder="Max" min="0">
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>

                        <!-- Sort Options -->
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">Sort By</label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="books_count" {{ request('sort_by') == 'books_count' ? 'selected' : '' }}>Books Count</option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="sort_order" class="form-label">Order</label>
                            <select class="form-select" id="sort_order" name="sort_order">
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-filter me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($categories->count() > 0)
                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded bg-white text-primary">
                                                    <i class="bx bx-category"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">{{ $categories->count() }}</h4>
                                                <small>Total Categories</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded bg-white text-success">
                                                    <i class="bx bx-book"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">{{ $categories->sum('livres_count') }}</h4>
                                                <small>Total Books</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded bg-white text-info">
                                                    <i class="bx bx-trending-up"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">{{ $categories->where('livres_count', '>', 0)->count() }}</h4>
                                                <small>Active Categories</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded bg-white text-warning">
                                                    <i class="bx bx-bar-chart"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">{{ number_format($categories->avg('livres_count'), 1) }}</h4>
                                                <small>Avg Books/Category</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Books Count</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $categorie)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initial bg-label-primary rounded">
                                                            <i class="bx bx-category"></i>
                                                        </span>
                                                    </div>
                                                    <span class="fw-medium">{{ $categorie->nom }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($categorie->description)
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $categorie->description }}">
                                                        {{ $categorie->description }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">No description</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($categorie->livres_count > 0)
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-label-{{ $categorie->livres_count >= 10 ? 'success' : ($categorie->livres_count >= 5 ? 'warning' : 'info') }}">
                                                            {{ $categorie->livres_count }} book{{ $categorie->livres_count > 1 ? 's' : '' }}
                                                        </span>
                                                        @if($categorie->livres_count >= 10)
                                                            <i class="bx bx-trending-up text-success ms-1"></i>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge bg-label-secondary">0 books</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $categorie->created_at?->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('admin.categories.show', $categorie->id) }}">
                                                            <i class="bx bx-show me-1"></i> View
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('admin.categories.edit', $categorie->id) }}">
                                                            <i class="bx bx-edit me-1"></i> Edit
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('admin.categories.destroy', $categorie->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" 
                                                                    onclick="return confirm('Are you sure you want to delete this category?')">
                                                                <i class="bx bx-trash me-1"></i> Delete
                                                            </button>
                                                        </form>
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
                                <i class="bx bx-category display-4 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Categories Found</h5>
                            <p class="text-muted">Get started by creating your first category.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Create Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const filterSelects = document.querySelectorAll('#sort_by, #sort_order');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Auto-submit form on date change
    const dateInputs = document.querySelectorAll('#date_from, #date_to');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Search with debounce
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });

    // Book count range validation
    const booksMinInput = document.getElementById('books_min');
    const booksMaxInput = document.getElementById('books_max');
    
    function validateBookCountRange() {
        const min = parseInt(booksMinInput.value) || 0;
        const max = parseInt(booksMaxInput.value) || 0;
        
        if (min > 0 && max > 0 && min > max) {
            booksMaxInput.setCustomValidity('Max books must be greater than or equal to min books');
        } else {
            booksMaxInput.setCustomValidity('');
        }
    }
    
    booksMinInput.addEventListener('input', validateBookCountRange);
    booksMaxInput.addEventListener('input', validateBookCountRange);

    // Clear filters functionality
    document.querySelector('a[href*="clear"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Clear all form inputs
        document.querySelectorAll('input, select').forEach(input => {
            if (input.type === 'text' || input.type === 'date' || input.type === 'number') {
                input.value = '';
            } else if (input.type === 'select-one') {
                input.selectedIndex = 0;
            }
        });
        // Submit the form
        this.form.submit();
    });

    // Add loading state to form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Loading...';
            submitBtn.disabled = true;
        }
    });

    // Add tooltips for better UX
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
