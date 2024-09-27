<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    /**
     * Show all teams
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminIndex() {
        $teams = Team::with('users')->orderBy('title')->get();
        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Show a single team
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminShow($id) {
        // Get the team
        $team = Team::with([
            'questions.clients',
            'tasks.client'
        ])->find($id);

        if(!$team){
            flash('Team does not exist')->error();
            return redirect()->route('admin.teams.index');
        }

        return view('admin.teams.show', compact('team'));
    }

    /**
     * Show all on client's team
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientIndex() {
        $teams = Team::with('users')->orderBy('title')->get();
        return view('client.teams.index', compact('teams'));
    }
}
