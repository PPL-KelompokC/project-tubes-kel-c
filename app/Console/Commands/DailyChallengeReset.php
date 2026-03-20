<?php

namespace App\Console\Commands;

use App\Models\Challenge;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyChallengeReset extends Command
{
    protected $signature   = 'challenges:daily-reset';
    protected $description = 'Reset daily challenges: pick 3 random challenges for today and clear yesterday\'s selection.';

    public function handle(): int
    {
        DB::transaction(function () {
            // Step 1: Clear all current daily flags
            Challenge::query()->update([
                'is_daily'    => false,
                'active_date' => null,
            ]);

            // Step 2: Pick 3 random challenges and mark them active for today
            $picks = Challenge::inRandomOrder()->limit(3)->pluck('id');

            Challenge::whereIn('id', $picks)->update([
                'is_daily'    => true,
                'active_date' => today(),
            ]);

            $this->info('Daily challenges reset. Selected IDs: ' . $picks->implode(', '));
        });

        return self::SUCCESS;
    }
}
