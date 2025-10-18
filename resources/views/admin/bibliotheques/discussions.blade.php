@extends('layouts/contentNavbarLayout')

@section('title', 'Library Discussions')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0"><i class="bx bx-conversation me-2"></i>Discussions</h4>
          <small class="text-muted">{{ $bibliotheque->nom_bibliotheque }}</small>
        </div>
        <a href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-arrow-back"></i> Back
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>Status</th>
              <th>Comments</th>
              <th>Created</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($discussions as $d)
            <tr>
              <td>{{ $d->titre }}</td>
              <td>{{ $d->user->name ?? '—' }}</td>
              <td>
                @if($d->est_resolu)
                  <span class="badge bg-success">Resolved</span>
                @else
                  <span class="badge bg-warning">Open</span>
                @endif
              </td>
              <td><span class="badge bg-label-info">{{ $d->comments_count }}</span></td>
              <td>{{ $d->created_at->diffForHumans() }}</td>
              <td class="text-end">
                <a href="{{ route('front.bibliotheques.show', $bibliotheque->id) }}#discussion-{{ $d->id }}" class="btn btn-sm btn-outline-info">
                  <i class="bx bx-show"></i> View
                </a>
                <form action="{{ route('admin.discussions.destroy', $d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this discussion?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bx bx-trash"></i> Delete
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-muted">No discussions for this library.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        {{ $discussions->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
