<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $query = Product::with('category');
        $filters = $request->only('search' , 'category' , 'status');
        if ($request->has('search')) {
            $query = $this->productService->applyFilter($query , $filters);
        }
        $products = $query->paginate(config('setting.per_page'));
        $categories = Category::get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    { 
        try {
            $this->productService->create($request->validated());
            return redirect()->route('products.index')->with('success', __('main.store_success', ['model' => class_basename(Product::class)]));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', __('main.error_storing', ['model' => class_basename(Product::class), 'reason' => $e->getMessage()]));
        }
    }


    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $this->productService->update($product, $request->validated());
            return redirect()->route('products.index')->with('success', __('main.update_success', ['model' => class_basename(Product::class)]));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', __('main.error_updating', ['model' => class_basename(Product::class), 'reason' => $e->getMessage()]));
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->delete($product);
            return redirect()->route('products.index')->with('success', __('main.delete_success', ['model' => class_basename(Product::class)]));
        } catch (\Exception $e) {
            return back()->with('error', __('main.error_occurred'));
        }
        
    }
    


    
}