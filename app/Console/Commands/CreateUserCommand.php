<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Propaganistas\LaravelPhone\PhoneNumber;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stealth:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the first user for the application';

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
        $client = Client::create([
            'title' => 'Nights Watch'
        ]);
        $user = User::create([
            'first_name' => "Jon",
            'last_name' => "Snow",
            'email' => "jon@snow.com",
            'email_verified_at' => now(),
            'password' => Hash::make('St34lth!'),
            'phone' => PhoneNumber::make('13064451234')->ofCountry('CA'),
            'position' => 'Lord Commander',
            'client_id' => $client->id,
        ]);

    }
}
