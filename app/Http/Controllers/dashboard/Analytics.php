<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\BookEvent;
use Illuminate\Http\Request;

class Analytics extends Controller
{
  public function index()
  {
    $events = BookEvent::orderBy('date_evenement', 'desc')->get();
    return view('content.dashboard.dashboards-analytics', compact('events'));
  }
}
