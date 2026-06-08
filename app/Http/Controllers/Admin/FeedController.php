<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    private function getCommonData()
    {
        $stats = [
            'total' => Feed::count(),
            'active' => Feed::where('status', 'active')->count(),
            'hidden' => Feed::where('status', 'hidden')->count(),
        ];

        $members = User::has('feeds')
            ->withCount('feeds')
            ->orderBy('feeds_count', 'desc')
            ->take(10)
            ->get();

        return compact('stats', 'members');
    }

    public function index()
    {
        $data = $this->getCommonData();
        $feeds = Feed::with(['user', 'likes', 'comments.user'])->latest()->paginate(15);
        $status = 'all';

        return view('admin.feeds.index', array_merge($data, compact('feeds', 'status')));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $data = $this->getCommonData();
        
        $feeds = Feed::with(['user', 'likes', 'comments.user'])
            ->where('caption', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15)
            ->appends(['q' => $query]);

        return view('admin.feeds.index', array_merge($data, compact('feeds', 'query')));
    }

    public function filterByStatus($status)
    {
        $data = $this->getCommonData();
        
        $feeds = Feed::with(['user', 'likes', 'comments.user'])
            ->where('status', $status)
            ->latest()
            ->paginate(15);

        return view('admin.feeds.index', array_merge($data, compact('feeds', 'status')));
    }

    public function show(Feed $feed)
    {
        $feed->load(['user', 'likes', 'comments' => function($q) {
            $q->whereNull('parent_id')->with(['user', 'replies.user']);
        }]);

        return view('admin.feeds.show', compact('feed'));
    }

    public function hide(Feed $feed)
    {
        $feed->update(['status' => 'hidden']);
        return back()->with('success', 'Feed has been hidden.');
    }

    public function show_feed(Feed $feed)
    {
        $feed->update(['status' => 'active']);
        return back()->with('success', 'Feed is now visible.');
    }

    public function unhide(Feed $feed)
    {
        return $this->show_feed($feed);
    }

    public function destroy(Feed $feed)
    {
        // Delete media files if any
        if ($feed->media && is_array($feed->media)) {
            foreach ($feed->media as $media) {
                $path = is_array($media) ? ($media['path'] ?? null) : null;
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $feed->delete();
        return redirect()->route('admin.feeds.index')->with('success', 'Feed has been permanently deleted.');
    }

    public function storeComment(Request $request, Feed $feed)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $feed->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $request->input('parent_id'),
            'content' => $request->input('content'),
        ]);

        $feed->increment('comments_count');

        return back()->with('success', 'Comment posted!');
    }

    public function destroyComment(Feed $feed, $commentId)
    {
        $comment = $feed->comments()->findOrFail($commentId);

        // Allow admin to delete any comment
        if ($comment->image && str_starts_with($comment->image, '/storage/')) {
            $path = str_replace('/storage/', '', $comment->image);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $feed->decrement('comments_count');
        $comment->delete();

        return back()->with('success', 'Comment deleted!');
    }
}
