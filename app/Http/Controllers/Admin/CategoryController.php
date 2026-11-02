<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')->withCount('children', 'products')
            ->orderByRaw('parent_id NULLS FIRST')->orderBy('name')->paginate(25);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $categories = Category::whereNull('parent_id')->where('status', true)->orderBy('name')->get();
        return view('admin.categories.form', ['category' => new Category(), 'categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Global category created.');
    }

    public function edit(Category $category): View
    {
        $categories = Category::whereNull('parent_id')->where('status', true)
            ->whereKeyNot($category->id)->orderBy('name')->get();

        return view('admin.categories.form', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validatedData($request, $category);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $category->id);
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Global category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->exists() || $category->products()->exists()) {
            return back()->with('error', 'This category has child categories or assigned products. Reassign them before deleting.');
        }

        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180'],
            'parent_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('status', true)],
            'description' => ['nullable', 'string', 'max:5000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'meta_robots' => ['required', Rule::in(['index,follow', 'noindex,follow', 'noindex,nofollow'])],
            'status' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['parent_id']) && $category && (int) $data['parent_id'] === $category->id) {
            abort(422, 'A category cannot be its own parent.');
        }

        $data['status'] = $request->boolean('status');
        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'category';
        $slug = $base;
        $number = 2;

        while (Category::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $number++;
        }

        return $slug;
    }
}
