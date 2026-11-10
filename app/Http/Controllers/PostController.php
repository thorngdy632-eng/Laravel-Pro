<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // <-- Correct
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // <--- 1. Make sure Auth is imported
use Illuminate\Support\Facades\Storage; // <--- 2. Make sure Storage is imported

class PostController extends Controller
{
    /**
     * Display the posts belonging to the given category.
     */
    public function index(Category $category, Request $request): View
{
    $query = $category->posts();

    // Search by title
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->input('search') . '%');
    }

    $posts = $query->latest()
        ->paginate(5);    // ← 6 posts per page

    return view('posts.index', compact('posts', 'category'));
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
public function store(Request $request, Category $category)
{
    $validated = $request->validate([
        'title'   => 'required|max:255',
        'content' => 'required',
        'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')
            ->store('posts', 'public');
    }

    // MODIFY THIS LINE: Add user_id using Auth::id()
    $category->posts()->create(array_merge($validated, [
        'user_id' => Auth::id(),
    ]));

    return redirect()
        ->route('categories.show', $category)
        ->with('success', 'Post created!');
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
public function update(Request $request, Category $category, Post $post)
{
    $validated = $request->validate([
        'title'   => 'required|max:255',
        'content' => 'required',
        'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $validated['image'] = $request->file('image')
            ->store('posts', 'public');
    }

    $post->update($validated);

    return redirect()
        ->route('categories.posts.show', [$category, $post])
        ->with('success', 'Post updated!');
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
