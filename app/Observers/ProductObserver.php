<?php
namespace App\Observers;

use App\Models\Product;
use App\Events\LowStockAlert;
use App\Traits\HandelImages;

class ProductObserver
{
    use HandelImages;
    
    public function updated(Product $product): void
    {
        if ($product->wasChanged('stock_quantity') && $product->isLowStock()) {
            event(new LowStockAlert($product));
        }
        
        $originalImage = $product->getOriginal('image');
        if ($product->isDirty('image') && $originalImage) {
            $this->deleteImage($originalImage);
        }

    }


     public function deleted(Product $product): void
    {
        if ($product->image) {
            $this->deleteImage($product->image);
        }
    }
    


    
}