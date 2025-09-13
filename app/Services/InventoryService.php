<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Auth;

class InventoryService
{
    public function updateStock(int $productId, int $quantityChange, string $type, string $notes = null): Product
    {
        $product = Product::findOrFail($productId);
        $quantityBefore = $product->stock_quantity;
        $quantityAfter = $quantityBefore + $quantityChange;

        // Update product stock
        $product->update(['stock_quantity' => $quantityAfter]);

        // Log the inventory change
        InventoryLog::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'type' => $type,
            'quantity_before' => $quantityBefore,
            'quantity_changed' => $quantityChange,
            'quantity_after' => $quantityAfter,
            'notes' => $notes,
        ]);

        return $product->fresh();
    }

    public function getLowStockProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::where('is_active', true)
                     ->whereRaw('stock_quantity <= min_stock_level')
                     ->with('category')
                     ->get();
    }

    public function getInventoryReport(): array
    {
        $products = Product::with('category')->where('is_active', true)->get();
        
        $totalValue = $products->sum(function ($product) {
            return $product->stock_quantity * $product->cost_price;
        });

        $lowStockCount = $products->filter(function ($product) {
            return $product->isLowStock();
        })->count();

        return [
            'products' => $products,
            'total_products' => $products->count(),
            'total_value' => $totalValue,
            'low_stock_count' => $lowStockCount,
        ];
    }
}