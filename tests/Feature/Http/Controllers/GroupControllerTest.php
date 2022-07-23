<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

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
        $response = $this->post(route('groups.store'), $group->attributesToArray());

        // Extract the new group
        $group = Group::query()->where('name', $group->name)->first();

        // Confirm key is not null
        $this->assertNotNull($group->key);
    }
}
