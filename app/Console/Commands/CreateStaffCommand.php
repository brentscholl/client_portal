<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateStaffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stealth:create-staff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the Stealth Staff for the application.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Create Staff
        $justin = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Justin',
            'last_name' => 'Harris',
            'position' => 'Marketing Advisor',
            'email' => 'justin@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $michelle = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Michelle',
            'last_name' => 'Young',
            'position' => 'Marketing Advisor',
            'email' => 'michelle@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $brent = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Brent',
            'last_name' => 'Scholl',
            'position' => 'Web Developer',
            'email' => 'brent@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $dan = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Dan',
            'last_name' => 'Villanueva',
            'position' => 'Web Developer',
            'email' => 'dan@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $chase = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Chase',
            'last_name' => 'Milligan',
            'position' => 'Web Developer',
            'email' => 'chase@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $gill = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Gill',
            'last_name' => 'McCaskill',
            'position' => 'Copywriter',
            'email' => 'gill@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $jaden = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Jaden',
            'last_name' => 'Dirk',
            'position' => 'Copywriter',
            'email' => 'jaden@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $jason = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Jason',
            'last_name' => 'Kwok',
            'position' => 'Designer',
            'email' => 'jason@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $emanuel = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Emanuel',
            'last_name' => 'Diaz',
            'position' => 'Designer',
            'email' => 'emanuel@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $chris = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Chris',
            'last_name' => 'Kotelmach',
            'position' => 'Photographer',
            'email' => 'chris@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);

        $sean = User::factory()->create([
            'client_id' => null,
            'first_name' => 'Sean',
            'last_name' => 'Kotelmach',
            'position' => 'Videographer',
            'email' => 'sean@stealthmedia.com',
            'password' => Hash::make('St34lth!')
        ]);
    }
}
