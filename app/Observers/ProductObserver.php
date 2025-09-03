<?php
namespace App\Observers;

use App\Models\Product;
use App\Events\LowStockAlert;

class ProductObserver
{
    public function updated(Product $product): void
    {
        if ($product->wasChanged('stock_quantity') && $product->isLowStock()) {
            event(new LowStockAlert($product));
        }
    }
}