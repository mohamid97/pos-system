@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><i class="fas fa-cash-register me-2"></i>Sales</h1>
</div>

<!-- Filters Card -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('sales.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label for="date_from" class="form-label fw-bold">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label fw-bold">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label fw-bold">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Search by sale number or customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
            
            @if(request()->anyFilled(['date_from', 'date_to', 'search']))
            <div class="mt-2">
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Sales Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Sale #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Cashier</th>
                        <th class="text-end">Items</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-end">Payment Method</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="fw-bold">{{ $sale->sale_number }}</td>
                        <td>{{ $sale->completed_at->format('M d, Y h:i A') }}</td>
                        <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                        <td>{{ $sale->user?->name }}</td>
                        <td class="text-end">{{ $sale->items->count() }}</td>
                        <td class="text-end fw-bold">${{ number_format($sale->total_amount, 2) }}</td>
                        <td class="text-end">
                            <span class="badge bg-info">{{ ucfirst($sale->payment_method) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($sale->status === 'completed')
                            <form action="{{ route('sales.cancel', $sale) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to cancel this sale? This action cannot be undone.');">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-sm btn-danger" title="Cancel Sale">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            @if(request()->anyFilled(['date_from', 'date_to', 'search']))
                                No sales found matching your filters.
                            @else
                                No sales recorded yet.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($sales->hasPages())
<div class="mt-3">
    {{ $sales->links() }}
</div>
@endif

<!-- Summary Statistics -->
@if($sales->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h6 class="card-title">Total Sales</h6>
                <h4 class="mb-0">{{ $sales->total() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6 class="card-title">Total Revenue</h6>
                <h4 class="mb-0">${{ number_format($sales->sum('total_amount'), 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h6 class="card-title">Average Sale</h6>
                <h4 class="mb-0">${{ number_format($sales->avg('total_amount') ?? 0, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h6 class="card-title">Items Sold</h6>
                <h4 class="mb-0">{{ $sales->sum(function($sale) { return $sale->items->count(); }) }}</h4>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Set default date values for better UX
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 2).toISOString().split('T')[0];
        
        if (!document.getElementById('date_from').value) {
            document.getElementById('date_from').value = firstDayOfMonth;
        }
        if (!document.getElementById('date_to').value) {
            document.getElementById('date_to').value = today;
        }
    });
</script>
@endsection