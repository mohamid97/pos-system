<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use  App\Traits\HandelImages;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductService
{
    use HandelImages;
    public function create(array $data): Product
    {
        $data['is_active'] = $this->chekActive($data);
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->handleImageUpload($data['image'] , 'products');
        }
        // Generate SKU if not provided
        if (!isset($data['sku']) || empty($data['sku'])) {
            $data['sku'] = $this->generateSku($data['name'], $data['category_id']);
        }

        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $data['is_active'] = $this->chekActive($data);
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->handleImageUpload($data['image'] , 'products');
        }

        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }



    protected function generateSku(string $name, int $categoryId): string
    {
        $category = Category::find($categoryId);
        $categoryCode = $category ? strtoupper(substr($category->name, 0, 3)) : 'GEN';
        $nameCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $randomNumber = rand(1000, 9999);

        return $categoryCode . '-' . $nameCode . '-' . $randomNumber;
    }

    public function searchProducts(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')
                     ->where('is_active', true)
                     ->where(function ($q) use ($query) {
                         $q->where('name', 'like', "%{$query}%")
                           ->orWhere('sku', 'like', "%{$query}%")
                           ->orWhere('barcode', 'like', "%{$query}%");
                     })
                     ->get(); 
    }

    private function chekActive(array $data): bool
    {
        return isset($data['is_active']) ? 1 : 0;
    }


    public function applyFilter(Builder $query, $filters): Builder{
        if(isset($filters['search']) && $filters['search'] !== ''){
            $query = $this->applySearch($query , $filters['search']);
        }
        if(isset($filters['category']) && $filters['category'] !== ''){
            $query->where('category_id' , $filters['category']);
        }
        if(isset($filters['status']) && $filters['status'] !== ''){
            $query->where('is_active' , $filters['status']);
        }

        return $query;

    }

    private function applySearch(Builder $query , string $search): Builder{
        return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
    }





}