@extends('layouts/contentNavbarLayout')
@section('title', 'My Books')
@section('content')
<div class="container py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">My Books</h2>
    <a href="{{ route('contributor.livres.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> Create Book
    </a>
  </div>

  <!-- Search and Filter Section -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('contributor.livres.index') }}" class="row g-3">
        <!-- Search Bar -->
        <div class="col-md-4">
          <label for="search" class="form-label">Search</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bx bx-search"></i></span>
            <input type="text" class="form-control" id="search" name="search" 
                   value="{{ request('search') }}" placeholder="Search by title, author, or description...">
          </div>
        </div>

        <!-- Author Filter -->
        <div class="col-md-2">
          <label for="author" class="form-label">Author</label>
          <select class="form-select" id="author" name="author">
            <option value="">All Authors</option>
            @foreach($authors as $author)
              <option value="{{ $author }}" {{ request('author') == $author ? 'selected' : '' }}>
                {{ $author }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Format Filter -->
        <div class="col-md-2">
          <label for="format" class="form-label">Format</label>
          <select class="form-select" id="format" name="format">
            <option value="">All Formats</option>
            @foreach($formats as $format)
              <option value="{{ $format }}" {{ request('format') == $format ? 'selected' : '' }}>
                {{ strtoupper($format) }}
              </option>
            @endforeach
          </select>
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

        <!-- Popularity Filter -->
        <div class="col-md-3">
          <label for="popularity" class="form-label">Sort by Popularity</label>
          <select class="form-select" id="popularity" name="popularity">
            <option value="">Default (Newest First)</option>
            <option value="most_popular" {{ request('popularity') == 'most_popular' ? 'selected' : '' }}>
              Most Popular
            </option>
            <option value="least_popular" {{ request('popularity') == 'least_popular' ? 'selected' : '' }}>
              Least Popular
            </option>
            <option value="highest_rated" {{ request('popularity') == 'highest_rated' ? 'selected' : '' }}>
              Highest Rated
            </option>
            <option value="lowest_rated" {{ request('popularity') == 'lowest_rated' ? 'selected' : '' }}>
              Lowest Rated
            </option>
          </select>
        </div>

        <!-- Filter Buttons -->
        <div class="col-md-3 d-flex align-items-end gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-filter me-1"></i> Apply Filters
          </button>
          <a href="{{ route('contributor.livres.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-x me-1"></i> Clear
          </a>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      @if($livres->count() > 0)
        <!-- Statistics Cards -->
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-3">
                    <span class="avatar-initial rounded bg-white text-primary">
                      <i class="bx bx-book"></i>
                    </span>
                  </div>
                  <div>
                    <h4 class="mb-0">{{ $livres->count() }}</h4>
                    <small>Total Books</small>
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
                      <i class="bx bx-star"></i>
                    </span>
                  </div>
                  <div>
                    <h4 class="mb-0">{{ $livres->where('avis_avg_note', '>', 0)->count() }}</h4>
                    <small>Rated Books</small>
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
                      <i class="bx bx-message"></i>
                    </span>
                  </div>
                  <div>
                    <h4 class="mb-0">{{ $livres->sum('avis_count') }}</h4>
                    <small>Total Reviews</small>
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
                      <i class="bx bx-trending-up"></i>
                    </span>
                  </div>
                  <div>
                    <h4 class="mb-0">{{ number_format($livres->where('avis_avg_note', '>', 0)->avg('avis_avg_note') ?? 0, 1) }}</h4>
                    <small>Avg Rating</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Library</th>
                <th>Format</th>
                <th>Visibility</th>
                <th>Rating</th>
                <th>Reviews</th>
                <th>Uploaded</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($livres as $livre)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                      <span class="avatar-initial rounded bg-label-primary">
                        <i class="bx bx-book"></i>
                      </span>
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
                <td>{{ $livre->bibliotheque->nom_bibliotheque ?? '-' }}</td>
                <td>
                  <span class="badge bg-label-info">{{ strtoupper($livre->format ?? '-') }}</span>
                </td>
                <td>
                  <span class="badge bg-label-{{ $livre->visibilite == 'public' ? 'success' : 'warning' }}">{{ ucfirst($livre->visibilite) }}</span>
                </td>
                <td>
                  @if($livre->avis_avg_note)
                    <div class="d-flex align-items-center">
                      <span class="me-1">{{ number_format($livre->avis_avg_note, 1) }}</span>
                      <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                          <i class="bx bx-star{{ $i <= $livre->avis_avg_note ? '' : '-o' }}"></i>
                        @endfor
                      </div>
                    </div>
                  @else
                    <span class="text-muted">No ratings</span>
                  @endif
                </td>
                <td>
                  @if($livre->avis_count > 0)
                    <span class="badge bg-label-primary">{{ $livre->avis_count }} review{{ $livre->avis_count > 1 ? 's' : '' }}</span>
                  @else
                    <span class="text-muted">No reviews</span>
                  @endif
                </td>
                <td>{{ $livre->created_at->format('Y-m-d H:i') }}</td>
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
                      <div class="dropdown-divider"></div>
                      <form action="{{ route('contributor.livres.destroy', $livre->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">
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
        <div class="text-center py-5">
          <i class="bx bx-book display-1 text-muted mb-3"></i>
          <h5 class="text-muted">No books uploaded yet</h5>
          <p class="text-muted">Create your first book to start sharing</p>
          <a href="{{ route('contributor.livres.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Create Book
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const filterSelects = document.querySelectorAll('#author, #format, #popularity');
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

    // Clear filters functionality
    document.querySelector('a[href*="clear"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Clear all form inputs
        document.querySelectorAll('input, select').forEach(input => {
            if (input.type === 'text' || input.type === 'date') {
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
});
</script>
@endsection
