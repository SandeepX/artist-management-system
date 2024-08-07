<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): \Illuminate\Http\RedirectResponse
    {
        switch (auth()->user()?->role) {
            case 'super_admin':
                return redirect()->route('users.index');

            case 'artist_manager':
                return redirect()->route('artists.index');

            case 'artist':
                Auth::logout();
                return redirect()->route('artist.index');

            default:
                Auth::logout();
                return redirect()->back();
        }
    }
}
