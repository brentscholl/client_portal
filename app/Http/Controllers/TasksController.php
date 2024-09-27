<?php

    namespace App\Http\Controllers;

    use App\Models\Project;
    use App\Models\Task;

    class TasksController extends Controller
    {
        /**
         * Show all tasks
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminIndex() {
            // Get all projects
            $projects = Project::all();

            return view('admin.tasks.index', compact('projects'));
        }

        /**
         * Show a single task
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminShow($id) {
            // Get the task
            $task = Task::with(['client', 'project', 'phase', 'urls'])->find($id);

            if(!$task){
                flash('Task does not exist')->error();
                return redirect()->route('admin.tasks.index');
            }

            return view('admin.tasks.show', compact('task'));
        }

        /**
         * Show all tasks
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function clientIndex() {
            // Get all projects
            $projects = Project::all();

            return view('client.tasks.index', compact('projects'));
        }

        /**
         * Show a single task
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function clientShow($id) {
            // Get the task
            $task = Task::with(['client', 'project', 'phase', 'urls'])->find($id);

            if(!$task){
                flash('Task does not exist')->error();
                return redirect()->route('client.tasks.index');
            }

            abort_unless($task->client_id == auth()->user()->client_id, 403);

            return view('client.tasks.show', compact('task'));
        }
    }
