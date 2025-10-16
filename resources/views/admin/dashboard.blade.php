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
              <td>
                <a href="{{ route('admin.bibliotheques.show', $b->id) }}" class="btn btn-outline-primary btn-sm">
                  <i class="bx bx-search-alt"></i> Review
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
