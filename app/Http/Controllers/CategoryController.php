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

    public function index(Request $request)
    {

        $query = Category::query();
        if ($request->has('search')) {
            $query = $this->service->applySearch($query , $request->get('search'));
        }
        $categories = $query->latest()->paginate(config('setting.per_page'));
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
            return redirect()->route('categories.index')->with('success', __('main.store_success', ['model' => class_basename(Category::class)]));
        }catch (\Exception $e) {
            return back()->withInput()->with('error', __('main.error_storing' , ['model'=>class_basename(Category::class) , 'reason'=>$e->getMessage()] ) );
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
            return redirect()->route('categories.index')->with('success', __('main.update_success', ['model' => class_basename(Category::class)]));
        }catch (\Exception $e) {
            return back()->withInput()->with('error', __('main.error_updating' , ['model'=>class_basename(Category::class) , 'reason'=>$e->getMessage()] ));
        }

    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return redirect()->route('categories.index')->with('success', __('main.delete_success', ['model' => class_basename(Category::class)]));
    }

    
}