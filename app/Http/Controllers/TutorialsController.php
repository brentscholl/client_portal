<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialsController extends Controller
{
    /**
     * Show all tutorials
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminIndex() {
        return view('admin.tutorials.index');
    }

    /**
     * Show a single tutorial
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminShow($id) {
        // Get the tutorial
        $tutorial = Tutorial::autoloadShow()->find($id);

        if(!$tutorial){
            flash('Tutorial does not exist')->error();
            return redirect()->route('admin.tutorial.index');
        }

        return view('admin.tutorials.show', compact('tutorial'));
    }

    /**
     * Show all tutorials
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientIndex() {
        return view('client.tutorials.index');
    }

    /**
     * Show a single tutorial
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientShow($id) {
        // Get the tutorial
        $tutorial = Tutorial::autoloadShow()->find($id);

        if(!$tutorial){
            flash('Tutorial does not exist')->error();
            return redirect()->route('client.tutorial.index');
        }

        return view('client.tutorials.show', compact('tutorial'));
    }
}
