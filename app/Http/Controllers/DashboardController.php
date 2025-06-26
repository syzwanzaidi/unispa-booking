<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('dashboard', compact('user')); // Pass user data to the view
    }
}
