<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Packages\Create;
    use App\Http\Livewire\Admin\Projects\ShowAll;
    use App\Models\Package;
    use App\Models\Service;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class ServiceTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();
        /*
         * todo: add delete package test
         */

        /** @test */
        public function an_admin_can_open_the_create_package_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_package()
        {
            $this->actingAs(User::factory()->admin()->create());

            $service = Service::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Ecommerce')
                ->set('description', 'Online store')
                ->set('service', $service)
                ->call('createPackage');
            $response->assertHasNoErrors();
            $this->assertTrue(Package::where('title', 'Ecommerce')->exists());
            $this->assertTrue(Service::where('id', $service->id)->whereHas('packages')->exists());

            $response->assertStatus(200);
        }
    }
