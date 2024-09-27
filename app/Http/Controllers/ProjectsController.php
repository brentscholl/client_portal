<?php

    namespace App\Http\Controllers;

    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Task;

    class ProjectsController extends Controller
    {
        /**
         * Show all projects
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminIndex() {
            return view('admin.projects.index');
        }

        /**
         * Show single project
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminShow($id) {
            // Get the project
            $project = Project::autoloadShow()->find($id);

            if(!$project){
                flash('Project does not exist')->error();
                return redirect()->route('admin.projects.index');
            }

            return view('admin.projects.show', compact('project'));
        }

        /**
         * Show all projects
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function clientIndex() {
            return view('client.projects.index');
        }

        /**
         * Show single project
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function clientShow($id) {
            // Get the project
            $project = Project::autoloadShow()->find($id);

            if(!$project){
                flash('Project does not exist')->error();
                return redirect()->route('client.projects.index');
            }

            abort_unless($project->client_id == auth()->user()->client_id, 403);

            return view('client.projects.show', compact('project'));
        }
    }
