@extends('layouts/contentNavbarLayout')

@section('title', 'Book Events')

@section('content')
<div class="container">
    <h1>Liste des événements</h1>
    <a href="{{ route('book-events.create') }}" class="btn btn-primary">Nouvel événement</a>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-2">{{ $message }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Titre</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($events as $event)
            <tr>
                <td>{{ $event->id }}</td>
                <td>{{ $event->type }}</td>
                <td>{{ $event->titre }}</td>
                <td>{{ $event->date_evenement }}</td>
                <td>
                    <a href="{{ route('book-events.show', $event->id) }}" class="btn btn-info btn-sm">Voir</a>
                    <a href="{{ route('book-events.edit', $event->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('book-events.destroy', $event->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
