<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_guest_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('/login');
    }

    /** @test */
    public function a_guest_cannot_view_a_single_project()
    {
        $project = Project::factory()->create();

        $this->get($project->path())->assertRedirect('/login');
    }

    /** @test */
    public function a_guest_cannot_create_a_project()
    {
        $attributes = Project::factory()->raw();

        $this->post('/projects', $attributes)->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_project()
    {
        $this->actingAs(User::factory()->create());

        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $data)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $data);

        $this->get('/projects')->assertSee($data['title']);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_project_is_associated_with_a_user()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->user);
    }

    /** @test */
    public function an_authenticated_user_can_view_their_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_other_users_projects()
    {
        $user1 = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user1->id]);

        $user2 = User::factory()->create();
        $this->actingAs($user2);

        $this->get($project->path())->assertStatus(403);
    }
}
