<?php

namespace Database\Seeders;

use App\Models\Assignee;
use App\Models\Client;
use App\Models\Login;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Question;
use App\Models\Service;
use App\Models\Task;
use App\Models\TaskUrl;
use App\Models\User;
use Database\Factories\AssigneeFactory;
use Illuminate\Database\Seeder;

class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // ` php artisan migrate --seed -vv ` to debug

        // Create admin user
        User::factory()->create([
            'client_id' => null,
            'email'     => 'admin@stealthmedia.com',
        ]);
    }
}
