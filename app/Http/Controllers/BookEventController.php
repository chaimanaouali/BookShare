<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookEvent;
use App\Models\Defi;
use Carbon\Carbon;

class BookEventController extends Controller
{
    public function index()
    {
        $events = BookEvent::where('type', '!=', 'Test Event')->get();
        $recentDefis = Defi::with(['bookEvents:id,defi_id,titre'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        // Match existing view directory naming: resources/views/Book_events
        return view('Book_events.index', compact('events', 'recentDefis'));
    }

    public function create()
    {
        $defis = \App\Models\Defi::orderByDesc('created_at')->get();
        // File present as "creat.blade.php"
        return view('Book_events.creat', compact('defis'));
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
        
        // Determine status based on date
        $eventDate = Carbon::parse($data['date_evenement']);
        $today = Carbon::today();
        
        if ($eventDate->isSameDay($today)) {
            $data['status'] = 'en_cours';
        } elseif ($eventDate->isPast()) {
            $data['status'] = 'termine';
        } else {
            $data['status'] = 'a_venir';
        }
        
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
        
        // Determine status based on date
        $eventDate = Carbon::parse($data['date_evenement']);
        $today = Carbon::today();
        
        if ($eventDate->isSameDay($today)) {
            $data['status'] = 'en_cours';
        } elseif ($eventDate->isPast()) {
            $data['status'] = 'termine';
        } else {
            $data['status'] = 'a_venir';
        }
        
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

