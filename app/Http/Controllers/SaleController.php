<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected $saleService;
    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user'])->where('status', 'completed');

        if ($request->has('date_from') && $request->get('date_from')) {
            $query->whereDate('completed_at', '>=', $request->get('date_from'));
        }

        if ($request->has('date_to') && $request->get('date_to')) {
            $query->whereDate('completed_at', '<=', $request->get('date_to'));
        }

        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $sales = $query->orderBy('completed_at', 'desc')->paginate(15);

        return view('sales.index', compact('sales'));
    }


    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'user']);
        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale)
    {
        if ($this->saleService->cancelSale($sale)) {
            return redirect()->route('sales.index')->with('success', 'Sale cancelled successfully!');
        }

        return back()->with('error', 'Unable to cancel this sale.');
    }

    public function report(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'user_id']);
        $report = $this->saleService->getSalesReport($filters);

        return view('sales.report', compact('report', 'filters'));
    }



}