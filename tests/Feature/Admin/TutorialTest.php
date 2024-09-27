<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\Admin\Tutorials\Create;
use App\Http\Livewire\Admin\Tutorials\Edit;
use App\Http\Livewire\Admin\Tutorials\Show;
use App\Http\Livewire\Admin\Tutorials\ShowAll;
use App\Http\Livewire\Admin\Tutorials\TutorialCard;
use App\Http\Livewire\Admin\Tutorials\TutorialRow;
use App\Models\Action;
use App\Models\Client;
use App\Models\Package;
use App\Models\Service;
use App\Models\Tutorial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TutorialTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** Useful Commands ==================== **/
    // $response->dumpHeaders();
    // $response->dumpSession();
    // $response->dump();

    /** @test */
    public function an_admin_can_see_tutorial_index_page()
    {
        $this->actingAs(User::factory()->admin()->create());
        $response = $this->get(route('admin.tutorials.index'));
        $response->assertStatus(200);

        $response = Livewire::test(ShowAll::class);
        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_open_the_create_tutorial_form()
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.tutorials.index'))->assertSeeLivewire('admin.tutorials.create');

        $response = Livewire::test(Create::class)
            ->call('openSlideout');

        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_create_a_tutorial_for_client()
    {
        $this->actingAs(User::factory()->admin()->create());

        $client = Client::factory()->create();
        $response = Livewire::test(Create::class)
            ->call('load')
            ->set('title', 'Hitchhikers Guide To The Galaxy')
            ->set('body', $this->faker->sentence())
            ->set('video_url', 'https://www.loom.com/share/6b01327520b643e687bad43e861e40e6?sharedAppSource=personal_library')
            ->set('assign_type', 'client')
            ->call('assignClient', $client->id)
            ->call('createTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Tutorial::where('title', 'Hitchhikers Guide To The Galaxy')
            ->whereHas('clients')->exists());

        $response->assertStatus(200);
    }
    /** @test */
    public function an_action_is_created_when_an_admin_creates_a_tutorial_for_client()
    {
        $this->actingAs(User::factory()->admin()->create());

        $client = Client::factory()->create();
        $response = Livewire::test(Create::class)
            ->call('load')
            ->set('title', 'Hitchhikers Guide To The Galaxy')
            ->set('body', $this->faker->sentence())
            ->set('video_url', 'https://www.loom.com/share/6b01327520b643e687bad43e861e40e6?sharedAppSource=personal_library')
            ->set('assign_type', 'client')
            ->call('assignClient', $client->id)
            ->call('createTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Action::where('type', 'model_created')->where('actionable_id', $client->id)->exists());


        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_create_a_tutorial_for_service()
    {
        $this->actingAs(User::factory()->admin()->create());

        $service = Service::factory()->create();
        $response = Livewire::test(Create::class)
            ->call('load')
            ->set('title', 'Hitchhikers Guide To The Galaxy')
            ->set('body', $this->faker->sentence())
            ->set('video_url', 'https://www.loom.com/share/6b01327520b643e687bad43e861e40e6?sharedAppSource=personal_library')
            ->set('assign_type', 'service')
            ->call('setService', $service->id)
            ->call('createTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Tutorial::where('title', 'Hitchhikers Guide To The Galaxy')
            ->whereHas('services')->exists());

        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_create_a_tutorial_for_package()
    {
        $this->actingAs(User::factory()->admin()->create());

        $package = Package::factory()->create();
        $response = Livewire::test(Create::class)
            ->call('load')
            ->set('title', 'Hitchhikers Guide To The Galaxy')
            ->set('body', $this->faker->sentence())
            ->set('video_url', 'https://www.loom.com/share/6b01327520b643e687bad43e861e40e6?sharedAppSource=personal_library')
            ->set('assign_type', 'package')
            ->call('setService', $package->service_id)
            ->call('setPackage', $package->id)
            ->call('createTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Tutorial::where('title', 'Hitchhikers Guide To The Galaxy')
            ->whereHas('packages')->exists());

        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_see_the_edit_a_tutorial_form()
    {
        $this->actingAs(User::factory()->admin()->create());
        $tutorial = Tutorial::factory()->create();

        $this->get(route('admin.tutorials.show', $tutorial->id))->assertSeeLivewire('admin.tutorials.edit');

        $response = Livewire::test(Edit::class, ['tutorial' => $tutorial])
            ->call('openSlideout');

        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_edit_a_tutorial()
    {
        $this->actingAs(User::factory()->admin()->create());
        $tutorial = Tutorial::factory()->create();
        $clients = Client::factory()->count(3)->create();
        $tutorial->clients()->attach($clients);

        $response = Livewire::test(Edit::class, ['tutorial' => $tutorial])
            ->call('load')
            ->set('title', 'How to make Pho')
            ->call('saveTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Tutorial::where('title', 'How to make Pho')->exists());

        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_edit_a_tutorial_to_be_for_client()
    {
        $this->actingAs(User::factory()->admin()->create());
        $tutorial = Tutorial::factory()->create();
        $client = Client::factory()->create();
        $service = Service::factory()->create();
        $tutorial->services()->attach($service);

        $response = Livewire::test(Edit::class, ['tutorial' => $tutorial])
            ->call('load')
            ->set('title', 'How to make Pho')
            ->set('assign_type', 'client')
            ->call('assignClient', $client->id)
            ->call('saveTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Tutorial::where('title', 'How to make Pho')
            ->whereHas('clients')
            ->whereDoesntHave('services')
            ->exists());

        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_edit_a_tutorial_to_be_for_service()
    {
        $this->actingAs(User::factory()->admin()->create());
        $tutorial = Tutorial::factory()->create();
        $client = Client::factory()->create();
        $service = Service::factory()->create();
        $package = package::factory()->create();
        $tutorial->packages()->attach($package);

        $response = Livewire::test(Edit::class, ['tutorial' => $tutorial])
            ->call('load')
            ->set('title', 'How to make Pho')
            ->set('assign_type', 'service')
            ->call('setService', $service->id)
            ->call('saveTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Tutorial::where('title', 'How to make Pho')
            ->whereHas('services')
            ->whereDoesntHave('packages')
            ->exists());

        $response->assertStatus(200);
    }
    /** @test */
    public function an_admin_can_edit_a_tutorial_to_be_for_package()
    {
        $this->actingAs(User::factory()->admin()->create());
        $tutorial = Tutorial::factory()->create();
        $client = Client::factory()->create();
        $package = package::factory()->create();
        $tutorial->clients()->attach($client);

        $response = Livewire::test(Edit::class, ['tutorial' => $tutorial])
            ->call('load')
            ->set('title', 'How to make Pho')
            ->set('assign_type', 'package')
            ->call('setPackage', $package->id)
            ->call('saveTutorial');
        $response->assertHasNoErrors();
        $this->assertTrue(Tutorial::where('title', 'How to make Pho')
            ->whereHas('packages')
            ->whereDoesntHave('clients')
            ->exists());

        $response->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_delete_a_tutorial()
    {
        $this->actingAs(User::factory()->admin()->create());
        // Row
        //$tutorial1 = Tutorial::factory()->create();
        //$response = Livewire::test(TutorialRow::class, ['tutorial' => $tutorial1])
        //    ->call('destroy');
        //$response->assertStatus(200);
        //$this->assertFalse(Tutorial::where('id', $tutorial1->id)->exists());

        // Show
        $tutorial2 = Tutorial::factory()->create();
        $response = Livewire::test(Show::class, ['tutorial' => $tutorial2])
            ->call('destroy');
        $response->assertStatus(200);
        $response->assertRedirect(route('admin.tutorials.index'));
        $this->assertFalse(Tutorial::where('id', $tutorial2->id)->exists());

        // Card
        $tutorial2 = Tutorial::factory()->create();
        $response = Livewire::test(TutorialCard::class, ['tutorial' => $tutorial2])
            ->call('destroy');
        $response->assertStatus(200);
        $this->assertFalse(Tutorial::where('id', $tutorial2->id)->exists());
    }
}
