<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feed;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Display a listing of all feeds for admin moderation
     */
    public function index()
    {
        $feeds = Feed::with(['user', 'likes.user', 'comments' => function($q) {
                $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
            }])
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Feed::count(),
            'active' => Feed::active()->count(),
            'hidden' => Feed::hidden()->count(),
        ];

        $members = \App\Models\User::where('role', 'user')
            ->withCount('feeds')
            ->orderByDesc('feeds_count')
            ->take(10)
            ->get();

        return view('admin.feeds.index', compact('feeds', 'stats', 'members'));
    }

    /**
     * Display a specific feed with all details
     */
    public function show(Feed $feed)
    {
        $feed->load([
            'user', 
            'likes.user', 
            'comments' => function($q) {
                $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
            }
        ]);
        return view('admin.feeds.show', compact('feed'));
    }

    /**
     * Hide a feed post
     */
    public function hide(Feed $feed)
    {
        $feed->hide();

        return redirect()->route('admin.feeds.index')
            ->with('success', 'Feed post has been hidden.');
    }

    /**
     * Show a hidden feed post
     */
    public function show_feed(Feed $feed)
    {
        $feed->show();

        return redirect()->route('admin.feeds.index')
            ->with('success', 'Feed post has been made visible.');
    }

    /**
     * Delete a feed post permanently
     */
    public function destroy(Feed $feed)
    {
        $userName = $feed->user->name;
        $feed->delete();

        return redirect()->route('admin.feeds.index')
            ->with('success', "Feed post from {$userName} has been deleted.");
    }

    /**
     * Filter feeds by status
     */
    public function filterByStatus(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Feed::with('user');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $feeds = $query->latest()->paginate(15);

        $stats = [
            'total' => Feed::count(),
            'active' => Feed::active()->count(),
            'hidden' => Feed::hidden()->count(),
        ];

        return view('admin.feeds.index', compact('feeds', 'stats', 'status'));
    }

    /**
     * Search feeds by user name or caption content
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $feeds = Feed::with(['user', 'likes.user', 'comments' => function($q) {
                $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
            }])
            ->where('caption', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Feed::count(),
            'active' => Feed::active()->count(),
            'hidden' => Feed::hidden()->count(),
        ];

        $members = \App\Models\User::where('role', 'user')
            ->withCount('feeds')
            ->orderByDesc('feeds_count')
            ->take(10)
            ->get();

        return view('admin.feeds.index', compact('feeds', 'stats', 'query', 'members'));
    }
}
