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
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ChallengeController as AdminChallenge;
use App\Http\Controllers\Admin\BadgeController as AdminBadge;
use App\Http\Controllers\Admin\PostController as AdminPost;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\EventController as AdminEvent;
use App\Http\Controllers\Admin\ArticleController as AdminArticle;
use App\Http\Controllers\Admin\ReferralController as AdminReferral;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmission;

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
    Route::resource('users',      AdminUser::class);
    Route::resource('events',     AdminEvent::class);
    Route::resource('articles',   AdminArticle::class);
    Route::get('/referrals', [AdminReferral::class, 'index'])->name('referrals.index');

    // Submission moderation
    Route::get('/submissions', [AdminSubmission::class, 'index'])->name('submissions.index');
    Route::post('/submissions/{submission}/approve', [AdminSubmission::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{submission}/reject',  [AdminSubmission::class, 'reject'])->name('submissions.reject');
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

    // Challenge submission (camera capture + submit)
    Route::get('/challenges/{challenge}/submit',  [ChallengeSubmissionController::class, 'create'])->name('challenges.submit');
    Route::post('/challenges/{challenge}/submit', [ChallengeSubmissionController::class, 'store'])->name('challenges.submit.store');

    // Community Feed (verified submissions social wall)
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');

    // Leaderboard (real data)
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // Other pages
    Route::get('/carbon', fn() => view('carbon'))->name('carbon');
    Route::get('/map',    fn() => view('map'))->name('map');
    Route::get('/profile', fn() => view('profile'))->name('profile');
    Route::get('/badges', fn() => view('badges'))->name('badges');
    Route::get('/stats',  fn() => view('stats'))->name('stats');
    Route::get('/rewards', fn() => view('rewards'))->name('rewards');
    Route::get('/learn',  fn() => view('learn'))->name('learn');
    Route::get('/referral', fn() => view('referral'))->name('referral');
    Route::get('/notifications', fn() => view('notifications'))->name('notifications');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Fallback demo
Route::get('/demo/dashboard', fn() => view('dashboard'))->name('demo.dashboard');
