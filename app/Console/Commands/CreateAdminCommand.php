<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Propaganistas\LaravelPhone\PhoneNumber;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stealth:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the first admin for the application.';

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
        $user = User::create([
            'first_name' => config('clientportal.admin-user.first_name'),
            'last_name' => config('clientportal.admin-user.last_name'),
            'email' => config('clientportal.admin-user.email'),
            'email_verified_at' => now(),
            'password' => Hash::make(config('clientportal.admin-user.password')),
            'phone' => PhoneNumber::make(config('clientportal.admin-user.phone'))->ofCountry('CA'),
            'position' => config('clientportal.admin-user.position'),
            'client_id' => null,
        ]);

    }
}
