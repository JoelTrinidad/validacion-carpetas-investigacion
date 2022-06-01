<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\User;

class CarpetasInvestigacionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test to check that the user can't see the import form when he isn't authenticated
     *
     * @return void
     */
    public function test_user_cannot_view_an_import_file_form()
    {

        $response = $this->get('/import');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * test to check that the user can see the import form
     *
     * @return void
     */
    public function test_user_can_view_an_import_file_form_when_authenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/import');

        $response->assertStatus(200);
        $response->assertViewIs('excel');
    }

}
