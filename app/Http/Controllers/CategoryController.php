<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function create()
    {
        return view('category.category-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
            ],

            'color' => [
                'required',
                'in:blue,green,yellow,red,purple,gray',
            ],
        ]);

        $slug = Str::slug($request->name);

        $categoryExists = Auth::user()
            ->category()
            ->where('slug', $slug)
            ->exists();

        if ($categoryExists) {
            return back()
                ->withErrors([
                    'name' => 'You already have a category with this name.',
                ])
                ->withInput();
        }

        Auth::user()->category()->create([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        return view('category.category-edit', compact('category'));
    }

    public function update(Category $category, Request $request)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
            ],

            'color' => [
                'required',
                'in:blue,green,yellow,red,purple,gray',
            ],
        ]);

        $slug = Str::slug($request->name);

        $categoryExists = Auth::user()
            ->category()
            ->where('slug', $slug)
            ->where('id', '!=', $category->id)
            ->exists();

        if ($categoryExists) {
            return back()
                ->withErrors([
                    'name' => 'You already have another category with this name.',
                ])
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        if ($category->task()->exists()) {
            return back()->withErrors([
                'category' => 'You cannot delete this category because it has tasks linked to it.',
            ]);
        }

        $category->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Category deleted successfully!');
    }
}