<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->latest()->get();
        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('user');
        return view('admin.events.show', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $event->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Event status updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted successfully.');
    }
}
