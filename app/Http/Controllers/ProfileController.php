<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the user profile.
     */
    public function index()
    {
        return view('profile');
    }

    /**
     * Update the user's profile avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'avatar.required' => 'Please select an image file to upload.',
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'Only JPEG, PNG, JPG, and WEBP formats are supported.',
            'avatar.max' => 'The image size must not exceed 2MB.',
        ]);

        $user = auth()->user();

        // Check if user already has an avatar and delete the old file
        if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
            // Extract the path from the URL
            $oldPath = str_replace('/storage/', '', $user->avatar);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Store the new file
        $file = $request->file('avatar');
        $path = $file->store('profile', 'public');

        // Update the user's avatar field with the storage URL
        $user->avatar = Storage::url($path);
        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Profile picture updated successfully!');
    }
}
