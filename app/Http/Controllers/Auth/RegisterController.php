<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $referrer = null;
        if ($request->filled('ref')) {
            $referrer = User::where('referral_code', strtoupper($request->ref))->first();
        }

        // Generate unique referral code for the new user
        $firstName = strtoupper(preg_replace('/[^a-zA-Z]/', '', explode(' ', $request->name)[0] ?? 'ECO'));
        if (strlen($firstName) < 3) {
            $firstName = str_pad($firstName, 3, 'X');
        }
        do {
            $myReferralCode = $firstName . rand(1000, 9999);
        } while (User::where('referral_code', $myReferralCode)->exists());

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'location' => $request->location,
            'referral_code' => $myReferralCode,
            'referred_by' => $referrer ? $referrer->id : null,
            'points' => $referrer ? 50 : 0, // 50 bonus points for invitee
        ]);

        if ($referrer) {
            $referrer->increment('points', 75);
        }

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');
    }
}
