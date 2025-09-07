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
     
       try{
            $this->service->update($category, $request->validated());
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        }catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error Updating product: ' . $e->getMessage());
        }

    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    
}