@extends('layouts/contentNavbarLayout')

@section('title', 'My Reviews - Contributor')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Reviews</h5>
                    <p class="text-muted mb-0">View all reviews for your books</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['total_reviews'] }}</h4>
                                            <p class="mb-0">Total Reviews</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-star fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['total_books'] }}</h4>
                                            <p class="mb-0">My Books</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-book fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ number_format($stats['average_rating'], 1) }}/5</h4>
                                            <p class="mb-0">Average Rating</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-trending-up fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reviewer</th>
                                    <th>Book</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($avis as $avi)
                                <tr>
                                    <td>{{ $avi->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($avi->utilisateur->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $avi->utilisateur->name }}</h6>
                                                <small class="text-muted">{{ $avi->utilisateur->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $avi->livre->title }}</h6>
                                        <small class="text-muted">by {{ $avi->livre->author }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $avi->note)
                                                    <i class="bx bxs-star text-warning"></i>
                                                @else
                                                    <i class="bx bx-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1">({{ $avi->note }}/5)</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $avi->commentaire }}">
                                            {{ $avi->commentaire }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">
                                            {{ $avi->date_publication->format('d/m/Y') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-star fs-1 mb-2"></i>
                                            <p>No reviews found for your books yet</p>
                                            <small>Reviews from readers will appear here</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($avis->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $avis->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
