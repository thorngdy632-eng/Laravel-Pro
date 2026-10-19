<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(): View
    {
        $categories = Category::withCount('posts')
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category and its posts.
     */
    public function show(Category $category): View
    {
        $posts = $category->posts()
            ->with('user')
            ->latest()
            ->get();

        return view('categories.show', compact('category', 'posts'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $name = $request->validated('name');

        $category->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
