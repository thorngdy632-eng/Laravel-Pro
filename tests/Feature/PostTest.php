<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_page_is_displayed_for_a_category(): void
    {
        $category = Category::factory()->create(['name' => 'Technology']);
        Post::factory()->for($category)->create(['title' => 'A seeded post']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.posts.index', $category));

        $response
            ->assertOk()
            ->assertSee('Technology')
            ->assertSee('A seeded post');
    }

    public function test_only_posts_of_the_given_category_are_listed(): void
    {
        $category = Category::factory()->create();
        $otherCategory = Category::factory()->create();

        Post::factory()->for($category)->create(['title' => 'Belongs here']);
        Post::factory()->for($otherCategory)->create(['title' => 'Belongs elsewhere']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.posts.index', $category));

        $response
            ->assertOk()
            ->assertSee('Belongs here')
            ->assertDontSee('Belongs elsewhere');
    }

    public function test_post_create_page_is_displayed(): void
    {
        $category = Category::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.posts.create', $category));

        $response
            ->assertOk()
            ->assertSee($category->name);
    }

    public function test_post_can_be_created_inside_a_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('categories.posts.store', $category), [
                'title' => 'My first post',
                'content' => 'Some meaningful content.',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('categories.show', $category));

        $this->assertDatabaseHas('posts', [
            'title' => 'My first post',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_post_creation_requires_a_title_and_content(): void
    {
        $category = Category::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('categories.posts.store', $category), [
                'title' => '',
                'content' => '',
            ]);

        $response
            ->assertSessionHasErrors(['title', 'content'])
            ->assertRedirect();

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_post_can_be_viewed(): void
    {
        $post = Post::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.posts.show', [$post->category, $post]));

        $response
            ->assertOk()
            ->assertSee($post->title);
    }

    public function test_post_edit_page_is_displayed(): void
    {
        $post = Post::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.posts.edit', [$post->category, $post]));

        $response
            ->assertOk()
            ->assertSee($post->title);
    }

    public function test_post_can_be_updated(): void
    {
        $post = Post::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->put(route('categories.posts.update', [$post->category, $post]), [
                'title' => 'Updated title',
                'content' => 'Updated content.',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('categories.posts.show', [$post->category, $post]));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated title',
            'content' => 'Updated content.',
        ]);
    }

    public function test_post_can_be_deleted(): void
    {
        $post = Post::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('categories.posts.destroy', [$post->category, $post]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('categories.show', $post->category));

        $this->assertModelMissing($post);
    }

    public function test_guests_cannot_manage_posts(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->for($category)->create();

        $this->get(route('categories.posts.index', $category))->assertRedirect(route('login'));
        $this->get(route('categories.posts.create', $category))->assertRedirect(route('login'));
        $this->post(route('categories.posts.store', $category), [])->assertRedirect(route('login'));
        $this->get(route('categories.posts.show', [$category, $post]))->assertRedirect(route('login'));
        $this->get(route('categories.posts.edit', [$category, $post]))->assertRedirect(route('login'));
        $this->put(route('categories.posts.update', [$category, $post]), [])->assertRedirect(route('login'));
        $this->delete(route('categories.posts.destroy', [$category, $post]))->assertRedirect(route('login'));
    }
}
