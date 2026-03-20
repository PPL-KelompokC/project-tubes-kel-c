<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $events = Event::query()
            ->where('status', 'accepted')
            ->when($filter, function ($query, $filter) {
                return $query->where('type', $filter);
            })
            ->get();

        return view('map', [
            'events' => $events,
            'activeFilter' => $filter,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cleanup,workshop,nature,awareness,transport',
            'date' => 'required|date|after_or_equal:today',
            'description' => 'required|string',
            'x' => 'required|numeric|min:0|max:100',
            'y' => 'required|numeric|min:0|max:100',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['participants'] = 0;

        Event::create($validated);

        return redirect()->back()->with('success', 'Event suggestion submitted! Waiting for admin approval.');
    }
}
