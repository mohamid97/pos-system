<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {

        try{
            $this->service->create($request->validated());
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');

        }catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
        }

    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        $validated['is_active'] = $request->has('is_active');
        $this->service->update($category, $validated);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}