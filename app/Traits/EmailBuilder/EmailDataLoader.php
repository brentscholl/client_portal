<?php

    namespace App\Traits\EmailBuilder;

    use App\Models\Project;
    use App\Models\Question;
    use App\Models\Task;
    use App\Models\Tutorial;

    trait EmailDataLoader
    {
        public $data = [];

        public $layout_data = [];

        /**
         * Load data for component
         *
         * @param null $i
         */
        public function loadData($layout, $i = null, $sender = null) {
            $newLayout = null;
            if($layout) {
                if ($i) {
                    $newLayout = $this->individualLoader($layout, $i);
                } else {
                    foreach ($layout as $key => $value) {
                        $newLayout = $this->individualLoader($layout, $key);
                    }
                }
            }

            $this->data = [
                'email_signature' => $this->email_signature,
                'user'            => [
                    'fullname'  => $sender ? $sender->fullname : auth()->user()->fullname,
                    'position'  => $sender ? $sender->position : auth()->user()->position,
                    'avatarUrl' => $sender ? $sender->avatarUrl() : auth()->user()->avatarUrl(),
                ],
                'layout'          => $newLayout,
                'data'            => $this->layout_data,
            ];
        }

        public function individualLoader($layout, $i) {
            switch ( $layout[$i]['layout'] ) {
                case('tasks'):
                    $layout = $this->loadDataTasks($layout, $i);
                    break;
                case('projects'):
                    $layout = $this->loadDataProjects($layout, $i);
                    break;
                case('questions'):
                    $layout = $this->loadDataQuestions($layout, $i);
                    break;
                case('tutorials'):
                    $layout = $this->loadDataTutorials($layout, $i);
                    break;
                case('single_task'):
                    $layout = $this->loadDataSingleTask($layout, $i);
                    break;
                case('single_project'):
                    $layout = $this->loadDataSingleProject($layout, $i);
                    break;
                case('single_question'):
                    $layout = $this->loadDataSingleQuestion($layout, $i);
                    break;
                case('single_tutorial'):
                    $layout = $this->loadDataSingleTutorial($layout, $i);
                    break;
                case('alert'):
                    // $layout[$i]['data'] = $layout[$i]['inputs']['message'];
                    break;
                case('link_to_dashboard'):
                    break;
            }

            return $layout;
        }

        /**
         * Load Data for Task List Component
         *
         * @param $i
         */
        public function loadDataTasks($layout, $i) {
            $tasks_data_array = [];
            $tasks = Task::where('client_id', $this->client->id)->where('visible', '1')->select([
                'id',
                'title',
                'status',
                'due_date',
                'client_id',
                'project_id',
                'phase_id',
            ]);

            if ( $layout[$i]['inputs']['project'] ) {
                $tasks->where('project_id', $layout[$i]['inputs']['project']['id']);
            }

            if ( $layout[$i]['inputs']['phase'] ) {
                $tasks->where('phase_id', $layout[$i]['inputs']['phase']['id']);
            }

            $tasks->where(function ($s) use ($layout, $i) {
                $statuses = $layout[$i]['inputs']['statuses'];
                if ( ! $statuses['pending'] && ! $statuses['in-progress'] && ! $statuses['completed'] && ! $statuses['on-hold'] && ! $statuses['canceled'] ) {
                    $s->orWhere('status', '!=', null);
                } else {
                    if ( $statuses['pending'] ) {
                        $s->orWhere('status', 'pending');
                    }
                    if ( $statuses['in-progress'] ) {
                        $s->orWhere('status', 'in-progress');
                    }
                    if ( $statuses['completed'] ) {
                        $s->orWhere('status', 'completed');
                    }
                    if ( $statuses['on-hold'] ) {
                        $s->orWhere('status', 'on-hold');
                    }
                    if ( $statuses['canceled'] ) {
                        $s->orWhere('status', 'canceled');
                    }
                }
            });

            $tasks = $tasks->orderBy('due_date', 'asc')->get();
            foreach ( $tasks as $task ) {
                $task_data = [
                    'id'           => $task->id,
                    'title'        => $task->title,
                    'status'       => $task->status,
                    'priority'     => $task->priority,
                    'due_date'     => $task->due_date ? $task->due_date->format('M d, Y') : null,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('M d, Y') : '',
                    'project'      => $task->project->title,
                    'phase_step'   => $task->phase->step,
                    'phase_title'  => $task->phase->title,
                ];
                array_push($tasks_data_array, $task_data);
            }

            $this->layout_data[$i] = $tasks_data_array;

            $layout[$i]['inputs']['projects'] = Project::where('client_id', $this->client->id)->select(['id', 'title'])->get()->toArray();

            return $layout;
        }

        /**
         * Load Data for Projects List Component
         *
         * @param $i
         */
        public function loadDataProjects($layout, $i) {
            $projects_data_array = [];
            $projects = Project::with(['phases:id,project_id,title,step,status', 'service'])
                ->where('client_id', $this->client->id)
                ->where('visible', '1')
                ->select([
                    'id',
                    'title',
                    'status',
                    'due_date',
                    'completed_at',
                    'service_id',
                    'client_id',
                ]);

            $projects->where(function ($s) use ($layout, $i) {
                $statuses = $layout[$i]['inputs']['statuses'];
                if ( ! $statuses['pending'] && ! $statuses['in-progress'] && ! $statuses['completed'] && ! $statuses['on-hold'] && ! $statuses['canceled'] ) {
                    $s->orWhere('status', '!=', null);
                } else {
                    if ( $statuses['pending'] ) {
                        $s->orWhere('status', 'pending');
                    }
                    if ( $statuses['in-progress'] ) {
                        $s->orWhere('status', 'in-progress');
                    }
                    if ( $statuses['completed'] ) {
                        $s->orWhere('status', 'completed');
                    }
                    if ( $statuses['on-hold'] ) {
                        $s->orWhere('status', 'on-hold');
                    }
                    if ( $statuses['canceled'] ) {
                        $s->orWhere('status', 'canceled');
                    }
                }
            });

            $projects = $projects->orderBy('due_date', 'asc')->get();
            foreach ( $projects as $project ) {
                $project_data = [
                    'id'            => $project->id,
                    'title'         => $project->title,
                    'status'        => $project->status,
                    'completed_at'  => $project->completed_at ? $project->completed_at->format('M d, Y') : '',
                    'due_date'      => $project->due_date ? $project->due_date->format('M d, Y') : null,
                    'service_title' => $project->service->title,
                    'service_slug'  => $project->service->slug,
                    'phases'        => $project->phases->toArray(),
                ];
                array_push($projects_data_array, $project_data);
            }
            $this->layout_data[$i] = $projects_data_array;

            return $layout;
        }

        /**
         * Load Data for Questions List Component
         *
         * @param $i
         */
        public function loadDataQuestions($layout, $i) {
            $questions_data_array = [];
            $questions = Question::query();

            if ( $layout[$i]['inputs']['include_onboarding'] ) {
                $questions = $questions->orWhere(function ($query) use ($layout, $i) {
                    if ( $layout[$i]['inputs']['filter'] != 'all' ) {
                        switch ( $layout[$i]['inputs']['filter'] ) {
                            case('answered'): // Get answered questions
                                $query->where('is_onboarding', 1)->whereHas('answers', function ($a) {
                                    $a->where('client_id', $this->client->id);
                                });
                                break;
                            case('unanswered'): // Get unanswered questions
                                $query->where('is_onboarding', 1)->whereDoesntHave('answers', function ($a) {
                                    $a->where('client_id', $this->client->id);
                                });
                                break;
                        }
                    } else {
                        $query->where('is_onboarding', 1);
                    }
                });
            }
            if ( $layout[$i]['inputs']['project'] ) { // For Project
                $questions = $questions->orWhere(function ($query) use ($layout, $i) {
                    $project = Project::with(['service', 'packages'])->find($layout[$i]['inputs']['project']['id']);
                    $query->orWhereHas('projects', function ($p) use ($project) { // Get all questions attached to project
                        $p->where('questionable_id', $project->id);
                    })->orWhereHas('services', function ($s) use ($project) { // Get all questions attached to project's attached service
                        $s->where('questionable_id', $project->service->id);
                    })->orWhereHas('packages', function ($p) use ($project) { // Get all questions attached to project's attached packages
                        $p->whereIn('questionable_id', $project->packages->pluck('id')->toArray());
                    });
                });
            } else { // For Client
                $questions = $questions->orWhere(function ($query) use ($layout, $i) {
                    $query->orWhereHas('clients', function ($c) { // Get all questions attached to client
                        $c->where('questionable_id', $this->client->id);
                    })->orWhereHas('projects', function ($p) { // Get all questions attached to client's projects
                        $p->whereIn('questionable_id', $this->client->projects->pluck('id')->toArray());
                    })->orWhereHas('services', function ($s) { // Get all questions attached to client's attached services
                        $s->whereIn('questionable_id', $this->client->services->pluck('id')->toArray());
                    })->orWhereHas('packages', function ($p) { // Get all questions attached to client's attached packages
                        $package_ids = [];
                        foreach ( $this->client->projects as $project ) {
                            if ( $project->packages->count() > 0 ) {
                                array_push($package_ids, $project->packages->pluck('id')->toArray());
                            }
                        }
                        $p->whereIn('questionable_id', $package_ids);
                    });
                });
            }

            if ( $layout[$i]['inputs']['filter'] != 'all' ) {
                switch ( $layout[$i]['inputs']['filter'] ) {
                    case('answered'): // Get answered questions
                        $questions = $questions->whereHas('answers', function ($a) {
                            $a->where('client_id', $this->client->id);
                        });
                        break;
                    case('unanswered'): // Get unanswered questions
                        $questions = $questions->whereDoesntHave('answers', function ($a) {
                            $a->where('client_id', $this->client->id);
                        });
                        break;
                }
            }

            $questions = $questions->get();

            foreach ( $questions as $question ) {
                $answer = $question->answers->where('client_id', $this->client->id)->first();
                $files = [];
                if ( $answer ) {
                    if ( $answer->files->count() > 0 ) {
                        foreach ( $answer->files as $file ) {
                            array_push($files, ['src' => $file->src]);
                        }
                    }
                }
                $question_data = [
                    'id'                => $question->id,
                    'body'              => $question->body,
                    'tagline'           => $question->tagline,
                    'type'              => $question->type,
                    'choices'           => $question->choices,
                    'has_answer'        => $answer ? true : false,
                    'add_file_uploader' => $question->add_file_uploader,
                    'answer'            => [
                        'body'    => optional($answer)->answer,
                        'choices' => optional($answer)->choices,
                        'files'   => $files,
                    ],
                ];
                array_push($questions_data_array, $question_data);
            }
            $this->layout_data[$i] = $questions_data_array;
            $layout[$i]['inputs']['projects'] = Project::where('client_id', $this->client->id)->select(['id', 'title'])->get()->toArray();

            return $layout;
        }

        /**
         * Load Data for Questions List Component
         *
         * @param $i
         */
        public function loadDataTutorials($layout, $i) {
            $tutorials_data_array = [];
            $tutorials = Tutorial::query();

            if ( $layout[$i]['inputs']['project'] ) { // For Project
                $tutorials = $tutorials->orWhere(function ($query) use ($layout, $i) {
                    $project = Project::with(['service', 'packages'])->find($layout[$i]['inputs']['project']['id']);
                    $query->orWhereHas('services', function ($p) use ($project) { // Get all tutorials attached to project
                        $p->where('tutorialable_id', $project->service->id);
                    })->orWhereHas('packages', function ($p) use ($project) { // Get all tutorials attached to project's attached packages
                        $p->whereIn('tutorialable_id', $project->packages->pluck('id')->toArray());
                    });
                });
            } else { // For Client
                $tutorials = $tutorials->orWhere(function ($query) use ($layout, $i) {
                    $query->orWhereHas('clients', function ($c) { // Get all tutorials attached to client
                        $c->where('tutorialable_id', $this->client->id);
                    })->orWhereHas('services', function ($s) { // Get all tutorials attached to client's attached services
                        $s->whereIn('tutorialable_id', $this->client->services->pluck('id')->toArray());
                    })->orWhereHas('packages', function ($p) { // Get all tutorials attached to client's attached packages
                        $package_ids = [];
                        foreach ( $this->client->projects as $project ) {
                            if ( $project->packages->count() > 0 ) {
                                array_push($package_ids, $project->packages->pluck('id')->toArray());
                            }
                        }
                        $p->whereIn('tutorialable_id', $package_ids);
                    });
                });
            }

            $tutorials = $tutorials->get();

            foreach ( $tutorials as $tutorial ) {
                $tutorial_data = [
                    'id'        => $tutorial->id,
                    'title'     => $tutorial->title,
                    'body'      => $tutorial->body,
                    'video_url' => $tutorial->video_url,
                ];
                array_push($tutorials_data_array, $tutorial_data);
            }
            $this->layout_data[$i] = $tutorials_data_array;
            $layout[$i]['inputs']['projects'] = Project::where('client_id', $this->client->id)->select(['id', 'title'])->get()->toArray();

            return $layout;
        }

        /**
         * Load Data for Single Task Component
         *
         * @param $i
         */
        public function loadDataSingleTask($layout, $i) {
            if ( $layout[$i]['inputs']['project'] ) {
                if ( $layout[$i]['inputs']['phase'] ) {
                    $layout[$i]['inputs']['tasks'] = Task::where('client_id', $this->client->id)->where('phase_id', $layout[$i]['inputs']['phase']['id'])->get()->toArray();
                } else {
                    $layout[$i]['inputs']['tasks'] = Task::where('client_id', $this->client->id)->where('project_id', $layout[$i]['inputs']['project']['id'])->get()->toArray();
                }
            } else {
                $layout[$i]['inputs']['tasks'] = Task::where('client_id', $this->client->id)->get()->toArray();
            }

            $layout[$i]['inputs']['projects'] = Project::where('client_id', $this->client->id)->select(['id', 'title'])->get()->toArray();

            $task_id = $layout[$i]['inputs']['task_id'];
            $task = $task_id ? Task::find($layout[$i]['inputs']['task_id']) : null;

            if ( $task ) {
                $task_data = [
                    'id'           => $task->id,
                    'title'        => $task->title,
                    'status'       => $task->status,
                    'priority'     => $task->priority,
                    'due_date'     => $task->due_date ? $task->due_date->format('M d, Y') : null,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('M d, Y') : '',
                    'project'      => $task->project->title,
                    'phase_step'   => $task->phase->step,
                    'phase_title'  => $task->phase->title,
                ];
                $this->layout_data[$i] = $task_data;
            }

            return $layout;
        }

        /**
         * Load Data for Single Project Component
         *
         * @param $i
         */
        public function loadDataSingleProject($layout, $i) {
            if ( ! $layout[$i]['inputs']['projects'] ) {
                $layout[$i]['inputs']['projects'] = Project::where('client_id', $this->client->id)->select(['id', 'title'])->get()->toArray();
            }

            $project_id = $layout[$i]['inputs']['project'] ? $layout[$i]['inputs']['project']['id'] : null;
            $project = $project_id ? Project::find($project_id) : null;

            if ( $project ) {
                $project_data = [
                    'id'            => $project->id,
                    'title'         => $project->title,
                    'status'        => $project->status,
                    'completed_at'  => $project->completed_at ? $project->completed_at->format('M d, Y') : '',
                    'due_date'      => $project->due_date ? $project->due_date->format('M d, Y') : null,
                    'service_title' => $project->service->title,
                    'service_slug'  => $project->service->slug,
                    'phases'        => $project->phases->toArray(),
                ];
                $this->layout_data[$i] = $project_data;
            }

            return $layout;
        }

        /**
         * Load Data for Single Question Component
         *
         * @param $i
         */
        public function loadDataSingleQuestion($layout, $i) {
            $layout[$i]['inputs']['projects'] = Project::where('client_id', $this->client->id)->select(['id', 'title'])->get()->toArray();

            $questions = Question::query();

            if ( $layout[$i]['inputs']['include_onboarding'] ) {
                $questions = $questions->orWhere('is_onboarding', 1);
            }
            if ( $layout[$i]['inputs']['project'] ) { // For Project
                $questions = $questions->orWhere(function ($query) use ($layout, $i) {
                    $project = Project::with(['service', 'packages'])->find($layout[$i]['inputs']['project']['id']);
                    $query->orWhereHas('projects', function ($p) use ($project) { // Get all questions attached to project
                        $p->where('questionable_id', $project->id);
                    })->orWhereHas('services', function ($s) use ($project) { // Get all questions attached to project's attached service
                        $s->where('questionable_id', $project->service->id);
                    })->orWhereHas('packages', function ($p) use ($project) { // Get all questions attached to project's attached packages
                        $p->whereIn('questionable_id', $project->packages->pluck('id')->toArray());
                    });
                });
            } else { // For Client
                $questions = $questions->orWhere(function ($query) use ($layout, $i) {
                    $query->orWhereHas('clients', function ($c) { // Get all questions attached to client
                        $c->where('questionable_id', $this->client->id);
                    })->orWhereHas('projects', function ($p) { // Get all questions attached to client's projects
                        $p->whereIn('questionable_id', $this->client->projects->pluck('id')->toArray());
                    })->orWhereHas('services', function ($s) { // Get all questions attached to client's attached services
                        $s->whereIn('questionable_id', $this->client->services->pluck('id')->toArray());
                    })->orWhereHas('packages', function ($p) { // Get all questions attached to client's attached packages
                        $package_ids = [];
                        foreach ( $this->client->projects as $project ) {
                            if ( $project->packages->count() > 0 ) {
                                array_push($package_ids, $project->packages->pluck('id')->toArray());
                            }
                        }
                        $p->whereIn('questionable_id', $package_ids);
                    });
                });
            }
            $layout[$i]['inputs']['questions'] = $questions->select('id', 'body', 'is_onboarding', 'client_id')->get()->toArray();

            $question_id = $layout[$i]['inputs']['question_id'];
            $question = $question_id ? Question::with(['answers', 'files'])->find($layout[$i]['inputs']['question_id']) : null;
            if ( $question ) {
                $answer = $question->answers->where('client_id', $this->client->id)->first();
                $files = [];
                if ( $answer ) {
                    if ( $answer->files->count() > 0 ) {
                        foreach ( $answer->files as $file ) {
                            array_push($files, ['src' => $file->src]);
                        }
                    }
                }
                $question_data = [
                    'id'                => $question->id,
                    'body'              => $question->body,
                    'tagline'           => $question->tagline,
                    'type'              => $question->type,
                    'choices'           => $question->choices,
                    'has_answer'        => $answer ? true : false,
                    'add_file_uploader' => $question->add_file_uploader,
                    'answer'            => [
                        'body'    => optional($answer)->answer,
                        'choices' => optional($answer)->choices,
                        'files'   => $files,
                    ],
                ];

                $this->layout_data[$i] = $question_data;
            }

            return $layout;
        }

        /**
         * Load Data for Single Tutorial Component
         *
         * @param $i
         */
        public function loadDataSingleTutorial($layout, $i) {
            $layout[$i]['inputs']['projects'] = Project::where('client_id', $this->client->id)->select(['id', 'title'])->get()->toArray();

            $tutorials = Tutorial::query();

            if ( $layout[$i]['inputs']['project'] ) { // For Project
                $tutorials = $tutorials->orWhere(function ($query) use ($layout, $i) {
                    $project = Project::with(['service', 'packages'])->find($layout[$i]['inputs']['project']['id']);
                    $query->orWhereHas('services', function ($s) use ($project) { // Get all tutorials attached to project's attached service
                        $s->where('tutorialable_id', $project->service->id);
                    })->orWhereHas('packages', function ($p) use ($project) { // Get all tutorials attached to project's attached packages
                        $p->whereIn('tutorialable_id', $project->packages->pluck('id')->toArray());
                    });
                });
            } else { // For Client
                $tutorials = $tutorials->orWhere(function ($query) use ($layout, $i) {
                    $query->orWhereHas('clients', function ($c) { // Get all tutorials attached to client
                        $c->where('tutorialable_id', $this->client->id);
                    })->orWhereHas('services', function ($s) { // Get all tutorials attached to client's attached services
                        $s->whereIn('tutorialable_id', $this->client->services->pluck('id')->toArray());
                    })->orWhereHas('packages', function ($p) { // Get all tutorials attached to client's attached packages
                        $package_ids = [];
                        foreach ( $this->client->projects as $project ) {
                            if ( $project->packages->count() > 0 ) {
                                array_push($package_ids, $project->packages->pluck('id')->toArray());
                            }
                        }
                        $p->whereIn('tutorialable_id', $package_ids);
                    });
                });
            }
            $layout[$i]['inputs']['tutorials'] = $tutorials->select('id', 'title')->get()->toArray();

            $tutorial_id = $layout[$i]['inputs']['tutorial_id'];
            $tutorial = $tutorial_id ? Tutorial::with(['services', 'packages', 'clients'])->find($layout[$i]['inputs']['tutorial_id']) : null;
            if ( $tutorial ) {
                $tutorial_data = [
                    'id'        => $tutorial->id,
                    'title'     => $tutorial->title,
                    'body'      => $tutorial->body,
                    'video_url' => $tutorial->video_url,
                ];

                $this->layout_data[$i] = $tutorial_data;
            }

            return $layout;
        }
    }
