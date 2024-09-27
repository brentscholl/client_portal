<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClientScopeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_model_has_a_client_id_on_the_migration()
    {
        $now = now();
        $this->artisan('make:model Test -m');

        // Find the migration file and chit it has client_id on it
        $filename = Str::finish($now->format('Y_m_d_His'), '_create_tests_table.php');

        $this->assertTrue(File::exists(database_path('migrations\\'.$filename)));

        $this->assertStringContainsString('$table->unsignedBigInteger(\'client_id\')->index();',
            File::get(database_path('migrations\\'.$filename)));
        // Clean up
        File::delete(database_path('migrations\\'.$filename));
        File::delete(app_path('/Models/Test.php'));
    }

    ///** @test */
    //public function a_user_can_only_see_users_in_the_same_client() {
    //    $client1 = Client::factory()->create();
    //    $client2 = Client::factory()->create();
    //
    //    $user1 = User::factory()->create([
    //        'client_id' => $client1,
    //    ]);
    //
    //    User::factory(9)->create([
    //        'client_id' => $client1,
    //    ]);
    //
    //    User::factory(10)->create([
    //        'client_id' => $client2,
    //    ]);
    //
    //    auth()->login($user1);
    //    $this->assertEquals(10, User::count());
    //}

    /** @test */
    public function a_user_can_only_create_a_user_in_their_client() {
        $client1 = Client::factory()->create();

        $user1 = User::factory()->create([
            'client_id' => $client1,
        ]);

        auth()->login($user1);

        $createdUser = User::factory()->create();

        $this->assertTrue($createdUser->client_id == $user1->client_id);
    }

    /** @test */
    public function a_user_can_only_create_a_user_in_their_client_even_if_other_client_is_provided() {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        $user1 = User::factory()->create([
            'client_id' => $client1,
        ]);

        auth()->login($user1);

        $createdUser = User::factory()->make();
        $createdUser->client_id = $client2->id;
        $createdUser->save();

        $this->assertTrue($createdUser->client_id == $user1->client_id);
    }
}
