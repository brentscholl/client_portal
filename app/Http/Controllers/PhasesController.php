<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use Illuminate\Http\Request;

class PhasesController extends Controller
{
    /**
     * Show a single phase.
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminShow($id) {
        // Get the phase
        $phase = Phase::autoloadShow()->find($id);

        if(!$phase){
            flash('Phase does not exist')->error();
            return redirect()->route('admin.projects.index');
        }

        return view('admin.phases.show', compact('phase'));
    }

    /**
     * Show a single phase.
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientShow($id) {
        // Get the phase
        $phase = Phase::autoloadShow()->find($id);

        if(!$phase){
            flash('Phase does not exist')->error();
            return redirect()->route('admin.projects.index');
        }

        abort_unless($phase->client_id == auth()->user()->client_id, 403);

        return view('client.phases.show', compact('phase'));
    }
}
