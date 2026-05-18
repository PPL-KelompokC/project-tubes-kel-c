<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $balanceData = [
            'points' => $user->points,
            'earned_total' => $user->rewardTransactions()->where('status', 'completed')->sum('points_used') + $user->points, // Simple logic: current + spent
            'redeemed_total' => $user->rewardTransactions()->where('status', 'completed')->sum('points_used'),
            'this_month' => $user->rewardTransactions()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('points_used'),
        ];

        // Recalculate earned_total more accurately if needed based on submissions
        $earned_from_submissions = $user->submissions()->where('status', 'verified')->sum('points_awarded');
        $balanceData['earned_total'] = $earned_from_submissions;
        $balanceData['this_month_earned'] = $user->submissions()
            ->where('status', 'verified')
            ->whereMonth('verified_at', now()->month)
            ->whereYear('verified_at', now()->year)
            ->sum('points_awarded');

        $rewardItems = Reward::where('status', '!=', 'inactive')->get();
        
        $transactions = $user->rewardTransactions()
            ->with('reward')
            ->latest()
            ->get();

        // Include earned points from submissions in history for a complete view if requested
        $earnings = $user->submissions()
            ->with('challenge')
            ->where('status', 'verified')
            ->get()
            ->map(function($s) {
                return [
                    'id' => 'earn_' . $s->id,
                    'type' => 'earn',
                    'description' => 'Challenge: ' . $s->challenge->title,
                    'points' => $s->points_awarded,
                    'date' => $s->verified_at ? $s->verified_at->format('Y-m-d') : $s->updated_at->format('Y-m-d'),
                    'status' => 'completed',
                    'created_at' => $s->verified_at ?: $s->updated_at
                ];
            });

        $redeems = $transactions->map(function($t) {
            return [
                'id' => 'redeem_' . $t->id,
                'type' => 'redeem',
                'description' => 'Redeem: ' . $t->reward->name,
                'points' => $t->points_used,
                'date' => $t->created_at->format('Y-m-d'),
                'status' => $t->status,
                'created_at' => $t->created_at
            ];
        });

        $fullHistory = $earnings->concat($redeems)->sortByDesc('created_at')->values();

        return view('rewards', compact('balanceData', 'rewardItems', 'fullHistory', 'user'));
    }

    public function redeem(Request $request, Reward $reward)
    {
        $user = Auth::user();

        if ($reward->status !== 'active') {
            return response()->json(['message' => 'This reward is currently unavailable.'], 400);
        }

        if ($user->points < $reward->points_required) {
            return response()->json(['message' => 'Insufficient points.'], 400);
        }

        if ($reward->stock !== null && $reward->stock <= 0) {
            return response()->json(['message' => 'This reward is out of stock.'], 400);
        }

        try {
            DB::beginTransaction();

            // Create transaction
            $transaction = RewardTransaction::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'points_used' => $reward->points_required,
                'status' => 'pending', // Default to pending for admin approval
            ]);

            // Deduct points from user
            $user->decrement('points', $reward->points_required);

            // Deduct stock if not unlimited
            if ($reward->stock !== null) {
                $reward->decrement('stock');
            }

            DB::commit();

            return response()->json([
                'message' => 'Redemption successful! Your request is pending approval.',
                'new_balance' => $user->points,
                'transaction' => $transaction
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong. Please try again.'], 500);
        }
    }
}
