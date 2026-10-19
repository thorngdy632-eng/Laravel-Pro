<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_page_is_displayed(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.index'));

        $response->assertOk();
    }

    public function test_category_create_page_is_displayed(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.create'));

        $response->assertOk();
    }

    public function test_category_edit_page_is_displayed(): void
    {
        $category = Category::factory()->create(['name' => 'Laravel Tips']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.edit', $category));

        $response
            ->assertOk()
            ->assertSee('Laravel Tips');
    }

    public function test_category_can_be_created(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('categories.store'), [
                'name' => 'Laravel Tips',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Laravel Tips',
            'slug' => 'laravel-tips',
        ]);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::factory()->create(['name' => 'Laravel Tips']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('categories.store'), [
                'name' => 'Laravel Tips',
            ]);

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect();
    }

    public function test_category_and_its_posts_are_displayed(): void
    {
        $category = Category::factory()->has(Post::factory(2))->create([
            'name' => 'Laravel Tips',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.show', $category));

        $response
            ->assertOk()
            ->assertSee('Laravel Tips');

        $category->posts->each(fn ($post) => $response->assertSee($post->title));
    }

    public function test_category_can_be_updated(): void
    {
        $category = Category::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->put(route('categories.update', $category), [
                'name' => 'Renamed Category',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Renamed Category',
            'slug' => 'renamed-category',
        ]);
    }

    public function test_category_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('categories.destroy', $category));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('categories.index'));

        $this->assertModelMissing($category);
    }

    public function test_guests_cannot_manage_categories(): void
    {
        $category = Category::factory()->create();

        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->get(route('categories.create'))->assertRedirect(route('login'));
        $this->post(route('categories.store'), [])->assertRedirect(route('login'));
        $this->put(route('categories.update', $category), [])->assertRedirect(route('login'));
        $this->delete(route('categories.destroy', $category))->assertRedirect(route('login'));
    }
}
