<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\InventoryService;


class DashboardController extends Controller
{
    protected $reportService;
    protected $inventoryService;
        public function __construct(ReportService $reportService, InventoryService $inventoryService)
    {
        $this->reportService = $reportService;
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $stats = $this->reportService->getDashboardStats();
        $topProducts = $this->reportService->getTopSellingProducts(5);
        $salesChart = $this->reportService->getSalesChart(30);
        $lowStockProducts = $this->inventoryService->getLowStockProducts();

        return view('dashboard.index', compact(
            'stats',
            'topProducts',
            'salesChart',
            'lowStockProducts'
        ));
        
    }


    
}