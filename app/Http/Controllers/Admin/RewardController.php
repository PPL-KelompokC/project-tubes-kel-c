<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{
    public function index(Request $request)
    {
        $query = Reward::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rewards = $query->latest()->paginate(10);

        // Dashboard stats
        $stats = [
            'total_redeemed_points' => RewardTransaction::where('status', 'completed')->sum('points_used'),
            'popular_reward' => Reward::withCount(['transactions' => function($q) {
                $q->where('status', 'completed');
            }])->orderBy('transactions_count', 'desc')->first(),
            'monthly_transactions' => RewardTransaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.rewards.index', compact('rewards', 'stats'));
    }

    public function create()
    {
        return view('admin.rewards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'required|in:active,coming_soon,inactive',
            'category' => 'required|in:physical,digital,donation',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rewards', 'public');
            $validated['image'] = $path;
        }

        Reward::create($validated);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward created successfully.');
    }

    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    public function update(Request $request, Reward $reward)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'required|in:active,coming_soon,inactive',
            'category' => 'required|in:physical,digital,donation',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($reward->image) {
                Storage::disk('public')->delete($reward->image);
            }
            $path = $request->file('image')->store('rewards', 'public');
            $validated['image'] = $path;
        }

        $reward->update($validated);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward updated successfully.');
    }

    public function destroy(Reward $reward)
    {
        $reward->delete();
        return redirect()->route('admin.rewards.index')->with('success', 'Reward deleted successfully.');
    }

    public function toggleStatus(Reward $reward)
    {
        $newStatus = match($reward->status) {
            'active' => 'inactive',
            'inactive' => 'active',
            'coming_soon' => 'active',
            default => 'active'
        };

        $reward->update(['status' => $newStatus]);

        return back()->with('success', 'Status updated to ' . ucfirst($newStatus));
    }
}
