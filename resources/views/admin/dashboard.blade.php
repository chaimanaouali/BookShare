@extends('layouts/contentNavbarLayout')

@section('title', 'Admin Dashboard - Bibliothèques')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">All Bibliothèques</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Owner</th>
              <th># Books</th>
              <th># Discussions</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bibliotheques as $b)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-2">
                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-library"></i></span>
                  </div>
                  <span>{{ $b->nom_bibliotheque }}</span>
                </div>
              </td>
              <td>{{ $b->user->name ?? 'Unknown' }}</td>
              <td><span class="badge bg-label-info">{{ $b->livre_utilisateurs_count }}</span></td>
              <td><span class="badge bg-label-secondary">{{ $b->discussions_count }}</span></td>
              <td>
                <a href="{{ route('admin.bibliotheques.show', $b->id) }}" class="btn btn-outline-primary btn-sm">
                  <i class="bx bx-search-alt"></i> Review
                </a>
                <a href="{{ route('admin.bibliotheques.discussions', $b->id) }}" class="btn btn-outline-secondary btn-sm">
                  <i class="bx bx-conversation"></i> View Discussions
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-12 mt-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Latest Discussions</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Library</th>
              <th>Author</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($latestDiscussions as $d)
            <tr>
              <td>{{ $d->titre }}</td>
              <td>{{ $d->bibliotheque->nom_bibliotheque ?? '—' }}</td>
              <td>{{ $d->user->name ?? '—' }}</td>
              <td>{{ $d->created_at->diffForHumans() }}</td>
              <td>
                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#discussionModal{{ $d->id }}">
                  <i class="bx bx-show"></i> Open
                </button>
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
            <tr><td colspan="5" class="text-muted">No discussions yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Discussion Modals -->
@foreach($latestDiscussions as $d)
<div class="modal fade" id="discussionModal{{ $d->id }}" tabindex="-1" aria-labelledby="discussionModalLabel{{ $d->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="discussionModalLabel{{ $d->id }}">{{ $d->titre }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <strong>Library:</strong> {{ $d->bibliotheque->nom_bibliotheque ?? '—' }}
          </div>
          <div class="col-md-6">
            <strong>Author:</strong> {{ $d->user->name ?? '—' }}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <strong>Created:</strong> {{ $d->created_at->format('M d, Y H:i') }}
          </div>
          <div class="col-md-6">
            <strong>Last Updated:</strong> {{ $d->updated_at->format('M d, Y H:i') }}
          </div>
        </div>
        <hr>
        <div class="discussion-content">
          <h6>Discussion Content:</h6>
          <p>{{ $d->contenu ?? 'No content available.' }}</p>
        </div>
        @if($d->comments && $d->comments->count() > 0)
        <hr>
        <div class="comments-section">
          <h6>Comments ({{ $d->comments->count() }}):</h6>
          @foreach($d->comments as $comment)
          <div class="card mb-2">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <strong>{{ $comment->user->name ?? 'Anonymous' }}</strong>
                  <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
              </div>
              <p class="mt-2 mb-0">{{ $comment->contenu }}</p>
            </div>
          </div>
          @endforeach
        </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="{{ route('admin.bibliotheques.discussions', $d->bibliotheque_id) }}" class="btn btn-primary">View All Discussions</a>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection
