<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookEvent;

class BookEventController extends Controller
{
    public function index()
    {
        $events = BookEvent::all();
        // Match existing view directory naming: resources/views/Book_events
        return view('Book_events.index', compact('events'));
    }

    public function create()
    {
        // File present as "creat.blade.php"
        return view('Book_events.creat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'defi_id' => 'nullable|integer',
            'type' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_evenement' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/events'), $imageName);
            $data['image'] = 'images/events/' . $imageName;
        }

        BookEvent::create($data);

        return redirect()->route('book-events.index')
                         ->with('success','Événement créé avec succès.');
    }

    public function show(BookEvent $bookEvent)
    {
        return view('Book_events.show', compact('bookEvent'));
    }

    public function edit(BookEvent $bookEvent)
    {
        return view('Book_events.edit', compact('bookEvent'));
    }

    public function update(Request $request, BookEvent $bookEvent)
    {
        $request->validate([
            'defi_id' => 'nullable|integer',
            'type' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_evenement' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($bookEvent->image && file_exists(public_path($bookEvent->image))) {
                unlink(public_path($bookEvent->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/events'), $imageName);
            $data['image'] = 'images/events/' . $imageName;
        }

        $bookEvent->update($data);

        return redirect()->route('book-events.index')
                         ->with('success','Événement mis à jour avec succès.');
    }

    public function destroy(BookEvent $bookEvent)
    {
        $bookEvent->delete();

        return redirect()->route('book-events.index')
                         ->with('success','Événement supprimé avec succès.');
    }
}

