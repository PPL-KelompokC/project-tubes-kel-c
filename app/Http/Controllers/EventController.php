<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Notifications\UserJoinedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $events = Event::query()
            ->with('attendingUsers')
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
            'x' => 'required|numeric|min:-90|max:90',
            'y' => 'required|numeric|min:-180|max:180',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['participants'] = 0;

        Event::create($validated);

        return redirect()->back()->with('success', 'Event suggestion submitted! Waiting for admin approval.');
    }

    public function join(Request $request, Event $event)
    {
        $user = Auth::user();

        if ($event->attendingUsers()->where('user_id', $user->id)->exists()) {
            // Leave event
            $event->attendingUsers()->detach($user->id);
            $event->decrement('participants');
            $joined = false;
        } else {
            // Join event
            $event->attendingUsers()->attach($user->id);
            $event->increment('participants');
            $joined = true;
            
            // Notify event creator if they exist and are not the person joining
            if ($event->user && $event->user_id !== $user->id) {
                $event->user->notify(new UserJoinedEvent($user, $event));
            }
        }

        return response()->json([
            'success' => true,
            'joined' => $joined,
            'participantsCount' => $event->fresh()->participants
        ]);
    }
}
