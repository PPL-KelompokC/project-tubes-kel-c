<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Feed;
use App\Notifications\CommentReplied;
use App\Notifications\FeedCommented;
use App\Notifications\FeedLiked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    /**
     * Display all active feeds (global feed for users)
     */
    public function index()
    {
        $filter = request('filter', 'all');
        
        $query = Feed::with(['user', 'likes', 'comments' => function($q) {
                $q->whereNull('parent_id')->with(['user', 'replies.user']);
            }])
            ->active();  // Only show active feeds, not hidden

        // Apply filter if not 'all'
        if ($filter !== 'all') {
            $query->where('feed_type', $filter);
        }

        $feeds = $query->latest()->paginate(15);

        return view('feed', compact('feeds', 'filter'));
    }

    /**
     * Search feeds by caption
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        $feeds = Feed::with(['user', 'likes', 'comments' => function($q) {
                $q->whereNull('parent_id')->with(['user', 'replies.user']);
            }])
            ->active()
            ->where('caption', 'like', '%' . $query . '%')
            ->latest()
            ->paginate(15)
            ->appends(['q' => $query]);

        return view('feed', compact('feeds', 'query'));
    }

    /**
     * Show a single feed post
     */
    public function show(Feed $feed)
    {
        // Only show active feeds unless user is the owner or admin
        if ($feed->status !== 'active' && $feed->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'This post is not available.');
        }

        $feed->load(['user', 'likes', 'comments' => function($q) {
            $q->whereNull('parent_id')->with(['user', 'replies.user']);
        }]);

        return view('feed.show', compact('feed'));
    }

    /**
     * Store a new feed post from authenticated user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'required|string|min:1|max:5000',
            'media' => 'nullable|array|max:5',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov|max:10240', // 10MB per file
        ]);

        $mediaData = [];

        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if (!$file) continue;
                
                $path = $file->store('feeds', 'public');
                $mediaData[] = [
                    'url' => Storage::url($path),
                    'path' => $path,
                    'type' => $this->getMimeType($file),
                ];
            }
        }

        // Create feed post with authenticated user
        Feed::create([
            'user_id' => auth()->id(),
            'caption' => $validated['caption'],
            'media' => !empty($mediaData) ? $mediaData : null,
            'status' => 'active',  // Default to active
            'feed_type' => 'post', // User posts are type 'post'
            'likes_count' => 0,
            'comments_count' => 0,
        ]);

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.feeds.index')
                ->with('success', 'Your announcement has been posted! 📢');
        }

        return redirect()->route('feed')
            ->with('success', 'Your post has been shared! 🌱');
    }

    /**
     * Toggle a like on a feed post
     */
    public function toggleLike(Feed $feed)
    {
        $like = $feed->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $feed->decrement('likes_count');
        } else {
            $feed->likes()->create(['user_id' => auth()->id()]);
            $feed->increment('likes_count');

            // Notify post owner (don't notify self)
            if ($feed->user_id !== auth()->id()) {
                $feed->user->notify(new FeedLiked($feed, auth()->user()));
            }
        }

        return back();
    }

    /**
     * Store a new comment for a feed post
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

        $newComment = $feed->comments()->latest()->first();
        $parentCommentOwnerId = null;

        // If it's a reply, notify the parent comment author
        if ($request->input('parent_id')) {
            $parentComment = Comment::find($request->input('parent_id'));
            if ($parentComment) {
                $parentCommentOwnerId = $parentComment->user_id;
                
                if ($parentCommentOwnerId !== auth()->id()) {
                    $parentComment->user->notify(new CommentReplied($newComment, auth()->user()));
                }
            }
        }

        // Notify post owner about the comment (don't notify self, and don't duplicate if they already got a reply notification)
        if ($feed->user_id !== auth()->id() && $feed->user_id !== $parentCommentOwnerId) {
            $feed->user->notify(new FeedCommented($feed, $newComment, auth()->user()));
        }

        return back()->with('success', 'Comment posted!');
    }

    /**
     * Determine media type from file
     */
    private function getMimeType($file): string
    {
        $mimeType = $file->getMimeType();
        
        if (str_starts_with($mimeType, 'image')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video')) {
            return 'video';
        }
        
        return 'file';
    }

    /**
     * Show edit form for a feed post
     */
    public function edit(Feed $feed)
    {
        // Authorization: only the owner can edit
        if ($feed->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to edit this post.');
        }

        return view('feed.edit', compact('feed'));
    }

    /**
     * Update a feed post
     */
    public function update(Request $request, Feed $feed)
    {
        // Authorization: only the owner can update
        if ($feed->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to update this post.');
        }

        $validated = $request->validate([
            'caption' => 'required|string|min:1|max:5000',
            'media' => 'nullable|array|max:5',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov|max:10240',
        ]);

        // Handle media uploads
        $mediaData = $feed->media ?? [];

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if (!$file) continue;
                
                $path = $file->store('feeds', 'public');
                $mediaData[] = [
                    'url' => Storage::url($path),
                    'path' => $path,
                    'type' => $this->getMimeType($file),
                ];
            }
        }

        // Update feed
        $feed->update([
            'caption' => $validated['caption'],
            'media' => !empty($mediaData) ? $mediaData : null,
        ]);

        return redirect()->route('feed')
            ->with('success', 'Your post has been updated! ✏️');
    }

    /**
     * Delete a feed post
     */
    public function destroy(Feed $feed)
    {
        // Authorization: only the owner can delete
        if ($feed->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to delete this post.');
        }

        // Delete media files
        if ($feed->media && is_array($feed->media)) {
            foreach ($feed->media as $media) {
                $path = is_array($media) ? ($media['path'] ?? null) : null;
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $feed->delete();

        return redirect()->route('feed')
            ->with('success', 'Your post has been deleted! 🗑️');
    }

    /**
     * Delete a comment from a feed post
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
