@extends('layouts.app')

@section('title', 'Sale Details - ' . $sale->sale_number)

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4"><i class="fas fa-receipt me-2"></i>Sale Details: {{ $sale->sale_number }}</h1>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Sales
            </a>
        </div>

        <div id="sale-details">
                    <!-- Sale Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Sale Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Sale Number:</strong> {{ $sale->sale_number }}</p>
                        <p><strong>Date & Time:</strong> {{ $sale->completed_at->format('M d, Y h:i A') }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $sale->status === 'completed' ? 'success' : 'danger' }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </p>
                        <p><strong>Cashier:</strong> {{ $sale->user?->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
                        <p><strong>Payment Method:</strong> <span class="badge bg-info">{{ ucfirst($sale->payment_method) }}</span></p>
                        <p><strong>Notes:</strong> {{ $sale->notes ?? 'No notes' }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Sale Items Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Sale Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" 
                                                 class="me-3">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->product->name }}</div>
                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Summary Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($sale->subtotal, 2) }}</span>
                        </div>
                        @if($sale->tax_amount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>${{ number_format($sale->tax_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($sale->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span class="text-danger">-${{ number_format($sale->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-2 fw-bold fs-5">
                            <span>Total Amount:</span>
                            <span>${{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Paid Amount:</span>
                            <span>${{ number_format($sale->paid_amount, 2) }}</span>
                        </div>
                        @if($sale->change_amount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Change Given:</span>
                            <span class="text-success">${{ number_format($sale->change_amount, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        </div>


        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if($sale->status === 'completed')
                <form action="{{ route('sales.cancel', $sale) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure you want to cancel this sale? This will restore inventory and cannot be undone.');">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i>Cancel Sale
                    </button>
                </form>
                @endif
            </div>
            <div>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list me-1"></i>Back to Sales List
                </a>
                <button onclick="printDetails('sale-details')" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i>Print 
                </button>
                <button onclick="printReceipt({{ $sale }})" class="btn btn-success no-print">
                 <i class="fas fa-receipt me-1"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>
<div id="receipt" class="print-only">
    <h3>Receipt</h3>
    <p>Customer: John Doe</p>
    <p>Product: Example Item</p>
    <p>Price: $100</p>
</div>

@endsection

@push('scripts')
<script>
function printDetails(divId) {
    let content = document.getElementById(divId).innerHTML;
    let printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Sales Details</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
            </head>
            <body onload="window.print(); window.close();">
                ${content}
            </body>
        </html>
    `);
    printWindow.document.close();
}



function printReceipt(sale) {
    console.log(sale);
    let receiptContent = `
        <div>
            <h3>${sale.sale_number}</h3>
            <p>Customer: ${sale.customer ? sale.customer.name : 'Walk-in Customer'}</p>
            <p>Product: ${sale.items[0].product.name}</p>
            <p>Payment Method: ${sale.payment_method}</p>
            <p>Total Amount: $${sale.total_amount.toFixed(2)}</p>
            <p>Date: ${new Date(sale.completed_at).toLocaleString()}</p>
        </div> 
    `;
    let originalContent = document.body.innerHTML;

    document.body.innerHTML = receiptContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}



</script>
@endpush