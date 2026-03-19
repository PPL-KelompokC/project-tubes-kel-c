<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Handle the landing page request.
     */
    public function __invoke()
    {
        // Selalu tampilkan landing page di URL utama (/)
        return view('landing');
    }
}
