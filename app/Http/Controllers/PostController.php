<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display the posts belonging to the given category.
     */
    public function index(Category $category): View
    {
        $posts = $category->posts()
            ->with('user')
            ->latest()
            ->get();

        return view('posts.index', compact('category', 'posts'));
    }

    /**
     * Show the form for creating a post in the given category.
     */
    public function create(Category $category): View
    {
        return view('posts.create', compact('category'));
    }

    /**
     * Store a newly created post under the given category.
     */
    public function store(StorePostRequest $request, Category $category): RedirectResponse
    {
        $post = $request->validated();
        $post['user_id'] = $request->user()->id;

        $category->posts()->create($post);

        return redirect()
            ->route('categories.show', $category)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified post.
     */
    public function show(Category $category, Post $post): View
    {
        $post->load(['category', 'user']);

        return view('posts.show', compact('category', 'post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Category $category, Post $post): View
    {
        return view('posts.edit', compact('category', 'post'));
    }

    /**
     * Update the specified post.
     */
    public function update(UpdatePostRequest $request, Category $category, Post $post): RedirectResponse
    {
        $post->update($request->validated());

        return redirect()
            ->route('categories.posts.show', [$category, $post])
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Category $category, Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('categories.show', $category)
            ->with('success', 'Post deleted successfully.');
    }
}
