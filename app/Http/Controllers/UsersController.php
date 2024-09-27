<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Show all users
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminIndex() {
        return view('admin.users.index');
    }

    /**
     * Show single user
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminShow($id) {
        // Get the user
        $user = User::autoloadShow()->find($id);

        if(!$user){
            flash('User does not exist')->error();
            return redirect()->route('admin.users.index');
        }

        return view('admin.users.show', compact('user'));
    }
    /**
     * Show single user
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientShow($id) {
        // Get the user
        $user = User::autoloadShow()->find($id);

        if(!$user){
            flash('User does not exist')->error();
            return redirect()->route('home');
        }

        return view('admin.users.show', compact('user'));
    }
}
