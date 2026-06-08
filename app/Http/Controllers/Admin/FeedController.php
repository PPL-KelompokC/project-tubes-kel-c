<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Store a new comment for a feed post (Admin can comment on user posts)
     */
    public function storeComment(Request $request, Feed $feed)
    {
        $request->validate([
            'content' => 'required_without:image|nullable|string|max:1000',
            'image' => 'required_without:content|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:comments,id',
        ], [
            'content.required_without' => 'Please provide either text or an image for your comment.',
            'image.required_without' => 'Please provide either text or an image for your comment.',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('comments', 'public');
            $imagePath = Storage::url($path);
        }

        $feed->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $request->input('parent_id'),
            'content' => $request->input('content'),
            'image' => $imagePath,
        ]);

        $feed->increment('comments_count');

        return back()->with('success', 'Comment posted! 💬');
    }

    /**
     * Delete a comment from a feed post (Admin can only delete their own comments)
     */
    public function destroyComment(Feed $feed, $commentId)
    {
        $comment = $feed->comments()->findOrFail($commentId);

        // Authorization: only the comment owner can delete
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to delete this comment.');
        }

        // Delete comment image if exists
        if ($comment->image && str_starts_with($comment->image, '/storage/')) {
            $path = str_replace('/storage/', '', $comment->image);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Decrement feed comment count
        $feed->decrement('comments_count');

        // Delete comment
        $comment->delete();

        return back()->with('success', 'Comment deleted! 🗑️');
    }
}
