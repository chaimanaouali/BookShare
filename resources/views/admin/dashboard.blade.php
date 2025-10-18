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
                <a href="{{ route('front.bibliotheques.show', $d->bibliotheque_id) }}#discussion-{{ $d->id }}" class="btn btn-sm btn-outline-info">
                  <i class="bx bx-show"></i> Open
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
            <tr><td colspan="5" class="text-muted">No discussions yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
