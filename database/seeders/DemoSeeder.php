<?php

namespace Database\Seeders;

use App\Models\Assignee;
use App\Models\Client;
use App\Models\Login;
use App\Models\Package;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Question;
use App\Models\Service;
use App\Models\Task;
use App\Models\TaskUrl;
use App\Models\User;
use Database\Factories\AssigneeFactory;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ` php artisan migrate --seed -vv ` to debug

        // Create Staff
        $justin = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Justin',
            'last_name' => 'Harris',
            'position' => 'Marketing Advisor'
        ]);

        $michelle = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Michelle',
            'last_name' => 'Young',
            'position' => 'Marketing Advisor'
        ]);

        $brent = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Brent',
            'last_name' => 'Scholl',
            'position' => 'Web Developer'
        ]);

        $dan = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Dan',
            'last_name' => 'Villanueva',
            'position' => 'Web Developer'
        ]);

        $chase = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Chase',
            'last_name' => 'Milligan',
            'position' => 'Web Developer'
        ]);

        $gill = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Gill',
            'last_name' => 'McCaskill',
            'position' => 'Copywriter'
        ]);

        $jaden = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Jaden',
            'last_name' => 'Dirk',
            'position' => 'Copywriter'
        ]);

        $jason = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Jason',
            'last_name' => 'Kwok',
            'position' => 'Designer'
        ]);

        $emanuel = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Emanuel',
            'last_name' => 'Diaz',
            'position' => 'Designer'
        ]);

        $chris = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Chris',
            'last_name' => 'Kotelmach',
            'position' => 'Photographer'
        ]);

        $sean = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Sean',
            'last_name' => 'Kotelmach',
            'position' => 'Videographer'
        ]);

        // Create Clients
        Client::factory()->count(4)->create();

        // Create users and projects for client
        foreach(Client::all() as $client) {
            User::factory()->count(5)->create([
                'client_id' => $client->id,
            ]);
            $projects = Project::factory()->count(3)->create([
               'client_id' => $client->id,
            ]);
            foreach($projects as $project){
                $project->client->services()->syncWithoutDetaching($project->service_id);
            }
            $i = rand(1,2);
            Assignee::factory()->create([
                'user_id' => $i == 1 ? $justin->id : $michelle->id,
                'assigneeable_type' => 'App\Models\Client',
                'assigneeable_id' => $client->id,
            ]);
        }

        // Create phase for projects
        foreach(Project::all() as $project){
            $copyPhase = Phase::factory()->create([
                'client_id' => $project->client_id,
                'project_id' => $project->id,
                'step' => '1',
                'title' => 'Copy',
            ]);
            $designPhase = Phase::factory()->create([
                'client_id' => $project->client_id,
                'project_id' => $project->id,
                'step' => '2',
                'title' => 'Design',
            ]);
            $devPhase = Phase::factory()->create([
                'client_id' => $project->client_id,
                'project_id' => $project->id,
                'step' => '3',
                'title' => 'Development',
            ]);
            $i = rand(1,2);
            Assignee::factory()->create([
                'user_id' => $i == 1 ? $gill->id : $jaden->id,
                'assigneeable_type' => 'App\Models\Phase',
                'assigneeable_id' => $copyPhase->id,
            ]);
            Assignee::factory()->create([
                'user_id' => $i == 1 ? $jason->id : $emanuel->id,
                'assigneeable_type' => 'App\Models\Phase',
                'assigneeable_id' => $designPhase->id,
            ]);
            Assignee::factory()->create([
                'user_id' => $i == 1 ? $brent->id : $dan->id,
                'assigneeable_type' => 'App\Models\Phase',
                'assigneeable_id' => $devPhase->id,
            ]);
        }

        // Create tasks for phases (which are part of projects and clients)
        foreach(Phase::all() as $phase){
            Task::factory()->count(4)->create([
                'client_id' => $phase->client_id,
                'project_id' => $phase->project_id,
                'phase_id' => $phase->id,
            ]);
        }

        // create a login record
        // foreach(User::all() as $user) {
        //     Login::factory()->count(5)->create([
        //         'user_id' => $user->id,
        //         'client_id' => $user->client_id,
        //     ]);
        // }

        // Questions
        $services = Service::all();

        foreach($services as $service){
            Package::factory()->count(3)->create([
               'service_id' => $service->id
            ]);

            $question_1 = Question::factory()->create();
            $question_2 = Question::factory()->create([
                'add_file_uploader' => true
            ]);
            $question_3 = Question::factory()->create([
                'type' => 'multi_choice',
                'choices' => json_encode(['Choice 1', 'Choice 2', 'Choice 3', 'Choice 4']),
            ]);
            $question_4 = Question::factory()->create([
                'type' => 'select',
                'choices' => json_encode(['Low', 'Med', 'High']),
                'add_file_uploader' => true
            ]);
            $question_5 = Question::factory()->create([
                'type' => 'boolean',
            ]);
            $service->questions()->save($question_1);
            $service->questions()->save($question_2);
            $service->questions()->save($question_3);
            $service->questions()->save($question_4);
            $service->questions()->save($question_5);
        }
    }
}
