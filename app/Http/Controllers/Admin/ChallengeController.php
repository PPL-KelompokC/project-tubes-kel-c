<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challenge;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::latest()->paginate(10);
        return view('admin.challenges.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty' => 'required|string',
            'points' => 'required|integer|min:0',
            'co2_saved' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
        ]);

        Challenge::create($validated);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge created successfully.');
    }

    public function edit(Challenge $challenge)
    {
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty' => 'required|string',
            'points' => 'required|integer|min:0',
            'co2_saved' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
        ]);

        $challenge->update($validated);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge updated successfully.');
    }

    public function destroy(Challenge $challenge)
    {
        $challenge->delete();
        return redirect()->route('admin.challenges.index')->with('success', 'Challenge deleted successfully.');
    }
}
