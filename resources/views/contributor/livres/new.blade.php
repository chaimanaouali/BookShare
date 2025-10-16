@extends('layouts/contentNavbarLayout')
@section('title', 'Add New Book Metadata')
@section('content')
<div class="container py-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Add New Book</h2>
    <a href="{{ url('/contributor/dashboard') }}" class="btn btn-outline-secondary">
      <i class="bx bx-home"></i> Dashboard
    </a>
  </div>
  <form action="{{ route('contributor.livres.store-metadata') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label">Title <span class="text-danger">*</span></label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Author <span class="text-danger">*</span></label>
      <input type="text" name="author" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">ISBN</label>
      <input type="text" name="isbn" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Create Book</button>
  </form>
</div>
@endsection
