<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\Admin\Clients\RepCard;
use App\Http\Livewire\Admin\Users\Create;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ClientRepTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** Useful Commands ==================== **/
    // $response->dumpHeaders();
    // $response->dumpSession();
    // $response->dump();

    /** @test */
    public function a_rep_can_me_assigned_primary_contact_for_client()
    {
        $this->actingAs(User::factory()->admin()->create());
        $client = Client::factory()->create();
        $user = User::factory()->create(['client_id' => $client->id]);

        $response = Livewire::test(RepCard::class, ['user' => $user, 'client' => $client])
            ->call('makePrimaryContact');
        $response->assertHasNoErrors();

        $this->assertTrue(Client::where('id', $client->id)
            ->where('primary_contact', $user->id)
            ->exists());
    }
}
