<?php

namespace App\Http\Controllers;

use App\Models\BookEvent;
use Illuminate\Http\Request;

class FrontEventController extends Controller
{
    public function index()
    {
        $events = BookEvent::where('type', '!=', 'Test Event')->orderByDesc('date_evenement')->get();
        $featured = $events->first();
        $others = $events->slice(1);
        return view('front.events.index', compact('events', 'featured', 'others'));
    }

    public function show(BookEvent $event)
    {
        $event->load(['defi.livres']);
        return view('front.events.show', compact('event'));
    }
}


