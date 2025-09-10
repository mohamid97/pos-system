<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosRequest;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Services\SaleService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PosController extends Controller
{
    protected $saleService;
    protected $productService;
    public function __construct(SaleService $saleService, ProductService $productService)
    {
        $this->saleService = $saleService;
        $this->productService = $productService;
    }
    public function index()
    {
        $categories = Category::where('is_active', true)->with('activeProducts')->get();
        $customers = Customer::all();
        return view('pos.index', compact('categories', 'customers'));
        
    }


    public function searchProducts(Request $request): JsonResponse
    {
        $query = $request->get('query');
        $products = $this->productService->searchProducts($query);

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    public function processSale(PosRequest $request): JsonResponse
    {

        try {
            $sale = $this->saleService->processSale($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Sale processed successfully',
                'sale' => $sale,
                'receipt_url' => route('pos.receipt', $sale->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing sale: ' . $e->getMessage()
            ], 500);
        }
    }


    // public function receipt($saleId)
    // {
    //     $sale = \App\Models\Sale::with(['items.product', 'customer', 'user'])->findOrFail($saleId);
    //     return view('pos.receipt', compact('sale'));
    // }

    

    
}