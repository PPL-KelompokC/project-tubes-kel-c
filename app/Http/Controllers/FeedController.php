<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    /**
     * Display all active feeds (global feed for users)
     */
    public function index()
    {
        $feeds = Feed::with(['user', 'likes', 'comments' => function($q) {
                $q->whereNull('parent_id')->with(['user', 'replies.user']);
            }])
            ->active()  // Only show active feeds, not hidden
            ->latest()
            ->paginate(15);

        return view('feed', compact('feeds'));
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
}
