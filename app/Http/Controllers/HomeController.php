<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Login;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show home dashboard
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        if (! auth()->check()) { // Not logged in
            return view('welcome');
        } else { // Logged in
            if(session()->has('client_id')) { // Is a client
                // Load client dashboard
                return view('dashboard');
            }
            // Is an admin. Load admin dashboard
            $subscribersCount = Client::count();
            $usersCount = User::count();
            $loginsCount = Login::count();

            return view('admin.dashboard', [
                'subscribersCount' => $subscribersCount,
                'usersCount' => $usersCount,
                'loginsCount' => $loginsCount,
            ]);
        }
    }
}
