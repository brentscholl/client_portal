<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Scopes\ClientScope;
use Illuminate\Http\Request;

class ImpersonationController extends Controller
{
    /**
     * Leave impersonation and go back to original logged in account.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function leave() {
        if(! session()->has('impersonate')){ // Trying to leave impersonation without having been impersonating
            abort(403);
        }
        // Login as the admin user in session
        auth()->login(User::withoutGlobalScope(ClientScope::class)->find(session('impersonate')));

        // Clear the impersonation
        session()->forget('impersonate');

        // Redirect back to home page
        return redirect('/');
    }
}
