<?php
namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Events\SaleCompleted;
use App\Events\LowStockAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleService
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function processSale(array $saleData): Sale
    {
        return DB::transaction(function () use ($saleData) {
            // Create the sale
            $sale = Sale::create([
                'sale_number' => Sale::generateSaleNumber(),
                'customer_id' => $saleData['customer_id'] ?? null,
                'user_id' => Auth::id(),
                'subtotal' => $saleData['subtotal'],
                'tax_amount' => $saleData['tax_amount'] ?? 0,
                'discount_amount' => $saleData['discount_amount'] ?? 0,
                'total_amount' => $saleData['total_amount'],
                'paid_amount' => $saleData['paid_amount'],
                'change_amount' => $saleData['change_amount'] ?? 0,
                'payment_method' => $saleData['payment_method'],
                'status' => 'completed',
                'completed_at' => now(),
                'notes' => $saleData['notes'] ?? null,
            ]);

            // Create sale items and update inventory
            foreach ($saleData['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                // Update inventory
                $this->inventoryService->updateStock(
                    $item['product_id'],
                    -$item['quantity'],
                    'sale',
                    "Sale #{$sale->sale_number}"
                );

                $product = \App\Models\Product::find($item['product_id']);
                if ($product->isLowStock()) {
                   
                    event(new LowStockAlert($product));
                }
            }

            // Update customer total purchases
            if ($sale->customer_id) {
                $this->updateCustomerPurchases($sale->customer_id, $sale->total_amount);
            }

            // Fire sale completed event
            event(new SaleCompleted($sale));

            return $sale->load('items.product', 'customer', 'user');
        });
    }

    protected function updateCustomerPurchases(int $customerId, float $amount): void
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $customer->increment('total_purchases', $amount);
        }
    }

    public function cancelSale(Sale $sale): bool
    {
        if ($sale->status !== 'completed') {
            return false;
        }

        return DB::transaction(function () use ($sale) {
            // Restore inventory
            foreach ($sale->items as $item) {
                $this->inventoryService->updateStock(
                    $item->product_id,
                    $item->quantity,
                    'return',
                    "Cancelled sale #{$sale->sale_number}"
                );
            }

            // Update customer total purchases
            if ($sale->customer_id) {
                $customer = Customer::find($sale->customer_id);
                if ($customer) {
                    $customer->decrement('total_purchases', $sale->total_amount);
                }
            }

            $sale->update(['status' => 'cancelled']);
            return true;
        });
    }

    public function getSalesReport(array $filters = []): array
    {
        $query = Sale::with(['items.product', 'customer', 'user'])
                    ->where('status', 'completed');

        if (isset($filters['date_from'])) {
            $query->whereDate('completed_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('completed_at', '<=', $filters['date_to']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $sales = $query->orderBy('completed_at', 'desc')->get();

        return [
            'sales' => $sales,
            'total_revenue' => $sales->sum('total_amount'),
            'total_tax' => $sales->sum('tax_amount'),
            'total_discount' => $sales->sum('discount_amount'),
            'total_sales' => $sales->count(),
        ];
    }
}