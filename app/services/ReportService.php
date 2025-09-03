<?php
namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Carbon\Carbon;

class ReportService
{
    public function getDashboardStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'today_sales' => Sale::whereDate('completed_at', $today)
                                ->where('status', 'completed')
                                ->sum('total_amount'),
            'today_orders' => Sale::whereDate('completed_at', $today)
                                 ->where('status', 'completed')
                                 ->count(),
            'month_sales' => Sale::where('completed_at', '>=', $thisMonth)
                                ->where('status', 'completed')
                                ->sum('total_amount'),
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('is_active', true)
                                          ->whereRaw('stock_quantity <= min_stock_level')
                                          ->count(),
        ];
    }

    public function getTopSellingProducts(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')
                     ->withCount(['saleItems as total_sold' => function ($query) {
                         $query->select(\DB::raw('sum(quantity)'));
                     }])
                     ->having('total_sold', '>', 0)
                     ->orderBy('total_sold', 'desc')
                     ->limit($limit)
                     ->get();
    }

    public function getSalesChart(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        $sales = Sale::selectRaw('DATE(completed_at) as date, SUM(total_amount) as total')
                    ->where('completed_at', '>=', $startDate)
                    ->where('status', 'completed')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

        $labels = [];
        $data = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('M d');
            
            $sale = $sales->firstWhere('date', $date);
            $data[] = $sale ? (float) $sale->total : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}