<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ChallengeSubmissionController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ChallengeController as AdminChallenge;
use App\Http\Controllers\Admin\BadgeController as AdminBadge;
use App\Http\Controllers\Admin\PostController as AdminPost;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\EventController as AdminEvent;
use App\Http\Controllers\Admin\ArticleController as AdminArticle;
use App\Http\Controllers\Admin\ReferralController as AdminReferral;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmission;
use App\Http\Controllers\Admin\FeedController as AdminFeed;
use App\Http\Controllers\Admin\RewardController as AdminReward;
use App\Http\Controllers\Admin\RewardTransactionController as AdminRewardTransaction;
use App\Http\Controllers\NotificationController;

// ── Public / Guest Only ──────────────────────────────────────────
Route::get('/', LandingController::class)->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login',  fn() => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register',  fn() => view('auth.register'))->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// ── Admin Routes ─────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',  [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('challenges', AdminChallenge::class);
    Route::resource('badges',     AdminBadge::class);
    Route::resource('posts',      AdminPost::class);
    Route::get('/users/export', [AdminUser::class, 'export'])->name('users.export');
    Route::resource('users',      AdminUser::class);
    Route::resource('events',     AdminEvent::class);
    Route::resource('articles',   AdminArticle::class);
    Route::get('/referrals', [AdminReferral::class, 'index'])->name('referrals.index');

    // Feed Management (Activity Feed Moderation)
    Route::get('/feeds', [AdminFeed::class, 'index'])->name('feeds.index');
    Route::get('/feeds/{feed}', [AdminFeed::class, 'show'])->name('feeds.show');
    Route::post('/feeds/{feed}/hide', [AdminFeed::class, 'hide'])->name('feeds.hide');
    Route::post('/feeds/{feed}/unhide', [AdminFeed::class, 'show_feed'])->name('feeds.unhide');
    Route::delete('/feeds/{feed}', [AdminFeed::class, 'destroy'])->name('feeds.destroy');
    Route::post('/feeds/{feed}/comments', [AdminFeed::class, 'storeComment'])->name('feeds.comments.store');
    Route::delete('/feeds/{feed}/comments/{comment}', [AdminFeed::class, 'destroyComment'])->name('feeds.comments.destroy');
    Route::get('/feeds/filter/by-status', [AdminFeed::class, 'filterByStatus'])->name('feeds.filter');
    Route::get('/feeds/search/query', [AdminFeed::class, 'search'])->name('feeds.search');

    // Submission moderation
    Route::get('/submissions', [AdminSubmission::class, 'index'])->name('submissions.index');
    Route::post('/submissions/{submission}/approve', [AdminSubmission::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{submission}/reject',  [AdminSubmission::class, 'reject'])->name('submissions.reject');

    // Rewards Management
    Route::patch('/rewards/{reward}/toggle-status', [AdminReward::class, 'toggleStatus'])->name('rewards.toggle-status');
    Route::resource('rewards', AdminReward::class);
    Route::get('/reward-transactions', [AdminRewardTransaction::class, 'index'])->name('rewards.transactions');
    Route::patch('/reward-transactions/{transaction}/status', [AdminRewardTransaction::class, 'updateStatus'])->name('rewards.transactions.update-status');
});

// ── Authenticated User Routes ────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return app(DashboardController::class)->index();
    })->name('dashboard');

    // Challenges list
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges');
    Route::post('/challenges/{challenge}/quick', [ChallengeController::class, 'quickComplete'])->name('challenges.quick');

    // Challenge submission (camera capture + submit)
    Route::get('/challenges/{challenge}/submit',  [ChallengeSubmissionController::class, 'create'])->name('challenges.submit');
    Route::post('/challenges/{challenge}/submit', [ChallengeSubmissionController::class, 'store'])->name('challenges.submit.store');

    // Community Feed (verified submissions social wall)
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::get('/feed/search', [FeedController::class, 'search'])->name('feed.search');
    Route::get('/feed/{feed}', [FeedController::class, 'show'])->name('feed.show');
    Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');
    Route::get('/feed/{feed}/edit', [FeedController::class, 'edit'])->name('feed.edit');
    Route::patch('/feed/{feed}', [FeedController::class, 'update'])->name('feed.update');
    Route::delete('/feed/{feed}', [FeedController::class, 'destroy'])->name('feed.destroy');
    Route::post('/feed/{feed}/like', [FeedController::class, 'toggleLike'])->name('feed.like.toggle');
    Route::post('/feed/{feed}/comments', [FeedController::class, 'storeComment'])->name('feed.comments.store');
    Route::delete('/feed/{feed}/comments/{comment}', [FeedController::class, 'destroyComment'])->name('feed.comments.destroy');

    // Leaderboard (real data)
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // Other pages
    Route::get('/carbon', fn() => view('carbon'))->name('carbon');
    Route::get('/map',    [EventController::class, 'index'])->name('map');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::post('/events/{event}/join', [EventController::class, 'join'])->name('events.join');
    // Profile & Avatar
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    Route::get('/badges', fn() => view('badges'))->name('badges');
    Route::get('/stats',  fn() => view('stats'))->name('stats');

    // Rewards (User Side)
    Route::get('/rewards', [App\Http\Controllers\RewardController::class, 'index'])->name('rewards');
    Route::post('/rewards/{reward}/redeem', [App\Http\Controllers\RewardController::class, 'redeem'])->name('rewards.redeem');

    Route::get('/learn',  [App\Http\Controllers\LearnController::class, 'learn'])->name('learn');
    Route::get('/learn/{slug}', [App\Http\Controllers\LearnController::class, 'show'])->name('learn.show');
    Route::get('/referral', fn() => view('referral'))->name('referral');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications', fn() => view('notifications'))->name('notifications');
    Route::get('/stats',  [StatsController::class, 'index'])->name('stats');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Fallback demo
Route::get('/demo/dashboard', fn() => view('dashboard'))->name('demo.dashboard');
