<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * Show all questions
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminIndex() {
        return view('admin.questions.index');
    }

    /**
     * Show single question
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminShow($id) {
        // Get the question
        $question = Question::autoloadShow()->find($id);

        if(!$question){
            flash('Question does not exist')->error();
            return redirect()->route('admin.questions.index');
        }

        return view('admin.questions.show', compact('question'));
    }

    public function adminShowAnswer($id) {
        $answer = Answer::find($id);

        return redirect()->route('admin.questions.show', $answer->question_id);
    }

    /**
     * Show all questions
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientIndex() {
        return view('client.questions.index');
    }

    /**
     * Show single question
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientShow($id) {
        // Get the question
        $question = Question::autoloadShow()->find($id);

        if(!$question){
            flash('Question does not exist')->error();
            return redirect()->route('client.questions.index');
        }

        return view('client.questions.show', compact('question'));
    }

    public function clientShowAnswer($id) {
        $answer = Answer::find($id);

        return redirect()->route('client.questions.show', $answer->question_id);
    }
}
