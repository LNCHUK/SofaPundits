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
        $group = Group::query()->where('uuid', $group->uuid)->first();

        // Confirm we are redirected after creation
        $response->assertRedirect(route('groups.show', $group->id));

        // Assert that this new Group is found in the database
        $this->assertDatabaseHas('groups', [
            'name' => $group->name,
            'key' => $group->key,
            'created_by' => $group->created_by
        ]);
    }
}
