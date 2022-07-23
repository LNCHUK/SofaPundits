<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_create_screen_returns_a_view()
    {
        $response = $this->get(route('groups.create'));

        $response->assertViewIs('groups.create')
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_create_a_new_group()
    {
        // Get a Group that isn't persisted to the DB
        $group = Group::factory()->make();

        // Attempt to store this group by posting to the 'store' route
        $response = $this->post(route('groups.store'), $group->attributesToArray());

        // Find the new Group from the DB
        $group = Group::query()->where('name', $group->name)->first();

        // Confirm we are redirected after creation
        $response->assertRedirect(route('groups.show', $group->id));

        // Assert that this new Group is found in the database
        $this->assertDatabaseHas('groups', [
            'name' => $group->name,
            'key' => $group->key,
            'created_by' => $group->created_by
        ]);
    }

    /** @test */
    public function a_group_has_a_uuid_set_on_creation()
    {
        // Get a group with no UUID set
        $group = Group::factory()->withNoUuid()->make();

        // POST the data to persist the group in the DB
        $this->post(route('groups.store'), $group->attributesToArray());

        // Extract the new group
        $group = Group::query()->where('name', $group->name)->first();

        // Confirm UUID is not null
        $this->assertNotNull($group->uuid);
    }

    /** @test */
    public function a_group_will_generate_a_valid_key_when_created()
    {
        // Get a group with no UUID set
        $group = Group::factory()->withNoKey()->make();

        // POST the data to persist the group in the DB
        $this->post(route('groups.store'), $group->attributesToArray());

        // Extract the new group
        $group = Group::query()->where('name', $group->name)->first();

        // Confirm key is not null
        $this->assertNotNull($group->key);
    }

    /** @test */
    public function the_user_creating_a_group_is_set_on_creation()
    {
        // Login as a new user so we can confirm the data is set correctly
        $user = User::factory()->create();
        $this->actingAs($user);

        // Get a Group with no created_by value
        $group = Group::factory()->make([
            'created_by' => null
        ]);

        // POST the data to persist the group in the DB
        $this->post(route('groups.store'), $group->attributesToArray());

        // Extract the new group
        $group = Group::query()->where('name', $group->name)->first();

        // Confirm created_by matches the authenticated user
        $this->assertEquals($group->created_by, $user->id);

        // Confirm the 'creator'' relation matches the expected user
        $this->assertTrue($group->creator->is($user));
    }

    /** @test */
    public function a_name_is_required_when_creating_a_group()
    {
        // Get a Group with no name
        $group = Group::factory()->make([
            'name' => null
        ]);

        // POST the data to persist the group in the DB
        $response = $this->post(route('groups.store'), $group->attributesToArray());

        // Confirm an error was raised for the missing name
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_name_must_not_be_more_than_150_characters_when_creating_a_group()
    {
        // Get a Group with a name of 150 characters
        $group = Group::factory()->make([
            'name' => Str::random(150),
        ]);

        // POST the data to persist the group in the DB
        $response = $this->post(route('groups.store'), $group->attributesToArray());

        // Confirm no errors were present with a correct length string
        $response->assertSessionHasNoErrors();

        // Get a Group with no name
        $group = Group::factory()->make([
            'name' => Str::random(151)
        ]);

        // POST the data to persist the group in the DB
        $response = $this->post(route('groups.store'), $group->attributesToArray());

        // Confirm an error was raised for the missing name
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_show_page_returns_the_correct_view()
    {
        $this->actingAs(User::factory()->create());

        $group = Group::factory()->create();

        $response = $this->get(route('groups.show', $group));

        $response->assertViewIs('groups.show')
            ->assertViewHas(['group' => $group])
            ->assertStatus(200);
    }
}
