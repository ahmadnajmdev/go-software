<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'items' => Category::ordered()->withCount('projects')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.categories.form', ['item' => new Category()]);
    }

    public function store(Request $request)
    {
        Category::create($this->data($request) + ['position' => Category::max('position') + 1]);

        return redirect()->route('admin.categories.index')->with('ok', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', ['item' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->data($request, $category));

        return redirect()->route('admin.categories.index')->with('ok', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        // projects.category_id is nullOnDelete, so projects survive uncategorised
        $category->delete();

        return redirect()->route('admin.categories.index')->with('ok', 'Category deleted.');
    }

    private function data(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name.en' => ['required', 'string', 'max:120'],
            'name.ar' => ['nullable', 'string', 'max:120'],
            'name.ckb' => ['nullable', 'string', 'max:120'],
            'slug' => [
                'nullable', 'string', 'max:120', 'alpha_dash',
                Rule::unique('categories', 'slug')->ignore($category),
            ],
        ]);

        // `nullable` leaves the key out entirely when the field is left blank,
        // so fall back to the English name rather than reading a missing key.
        $slug = $data['slug'] ?? null;
        $data['slug'] = Str::slug(filled($slug) ? $slug : $data['name']['en']);

        return $data;
    }
}
