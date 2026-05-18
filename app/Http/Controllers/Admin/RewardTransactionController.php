<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardTransaction;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = RewardTransaction::with(['user', 'reward']);

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('reward', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reward_id')) {
            $query->where('reward_id', $request->reward_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->latest()->paginate(15);
        $rewards = Reward::withTrashed()->get();

        return view('admin.rewards.transactions', compact('transactions', 'rewards'));
    }

    public function updateStatus(Request $request, RewardTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:completed,rejected'
        ]);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be updated.');
        }

        DB::transaction(function() use ($request, $transaction) {
            $transaction->update(['status' => $request->status]);

            // If rejected, refund points to user
            if ($request->status === 'rejected') {
                $user = $transaction->user;
                $user->increment('points', $transaction->points_used);
            }
        });

        return back()->with('success', 'Transaction status updated to ' . ucfirst($request->status));
    }
}
