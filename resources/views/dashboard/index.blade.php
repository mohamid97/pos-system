@extends('layouts.app')

@section('title', 'Dashboard - POS System')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Today's Sales</h6>
                    <h3 class="mb-0">${{ number_format($stats['today_sales'], 2) }}</h3>
                </div>
                <div class="display-6">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Today's Orders</h6>
                    <h3 class="mb-0">{{ $stats['today_orders'] }}</h3>
                </div>
                <div class="display-6">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Monthly Sales</h6>
                    <h3 class="mb-0">${{ number_format($stats['month_sales'], 2) }}</h3>
                </div>
                <div class="display-6">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Products</h6>
                    <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                </div>
                <div class="display-6">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sales Chart -->
    <div class="col-xl-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Sales Chart (Last 30 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-xl-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i>Top Selling Products</h5>
            </div>
            <div class="card-body">
                @forelse($topProducts as $product)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $product->name }}</h6>
                            <small class="text-muted">Sold: {{ $product->total_sold ?? 0 }} units</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">${{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>No sales data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
@if($lowStockProducts->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Min Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong><br>
                                                <small class="text-muted">{{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                                    </td>
                                    <td>{{ $product->min_stock_level }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Update Stock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Sales Chart
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($salesChart['labels']),
        datasets: [{
            label: 'Daily Sales ($)',
            data: @json($salesChart['data']),
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush