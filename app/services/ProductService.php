<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use  App\Traits\HandelImages;


class ProductService
{
    use HandelImages;
    public function create(array $data): Product
    {
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
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image
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
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

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
}