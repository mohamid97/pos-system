@extends('layouts.app')

@section('title', 'Point of Sale - POS System')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4"><i class="fas fa-shopping-cart me-2"></i>Point of Sale</h1>
    </div>
</div>

<div class="row">
    <!-- Products Section -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="fas fa-box me-2"></i>Products</h5>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="product-search" placeholder="Search products...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                <!-- Category Tabs -->
                <ul class="nav nav-pills mb-3" id="category-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-category="all">All Products</a>
                    </li>
                    @foreach($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link" data-category="{{ $category->id }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>

                <!-- Products Grid -->
                <div class="row" id="products-grid">
                    @foreach($categories as $category)
                        @foreach($category->activeProducts as $product)
                            <div class="col-lg-4 col-md-6 mb-3 product-item" data-category="{{ $category->id }}">
                                <div class="card pos-product-card" data-product-id="{{ $product->id }}" 
                                     data-product-name="{{ $product->name }}" 
                                     data-product-price="{{ $product->price }}"
                                     data-product-stock="{{ $product->stock_quantity }}">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                            <i class="fas fa-box fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body text-center">
                                        <h6 class="card-title mb-2">{{ $product->name }}</h6>
                                        <p class="card-text text-success fw-bold">${{ number_format($product->price, 2) }}</p>
                                        <small class="text-muted">Stock: {{ $product->stock_quantity }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h5>
            </div>
            <div class="card-body">
                <!-- Customer Selection -->
                <div class="mb-3">
                    <label class="form-label">Customer (Optional)</label>
                    <select class="form-select" id="customer-select">
                        <option value="">Walk-in Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cart Items -->
                <div id="cart-items" style="max-height: 300px; overflow-y: auto;">
                    <div class="text-center text-muted py-5" id="empty-cart">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Cart is empty</p>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (0%):</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 fw-bold">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>

                    <!-- Payment -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="payment-method">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount Paid</label>
                        <input type="number" class="form-control" id="paid-amount" step="0.01" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Change</label>
                        <input type="text" class="form-control" id="change-amount" readonly>
                    </div>

                    <!-- Action Buttons -->
                    <button class="btn btn-success w-100 mb-2" id="process-sale" disabled>
                        <i class="fas fa-check me-2"></i>Process Sale
                    </button>
                    <button class="btn btn-outline-secondary w-100" id="clear-cart">
                        <i class="fas fa-trash me-2"></i>Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];
let tax_rate = 0; // 0% tax

$(document).ready(function() {
    // Product search
    $('#product-search').on('input', function() {
        const query = $(this).val().toLowerCase();
        $('.product-item').each(function() {
            const productName = $(this).find('.card-title').text().toLowerCase();
            if (productName.includes(query) || query === '') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Category filter
    $('#category-tabs .nav-link').click(function() {
        $('#category-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
        
        const category = $(this).data('category');
        if (category === 'all') {
            $('.product-item').show();
        } else {
            $('.product-item').hide();
            $(`.product-item[data-category="${category}"]`).show();
        }
    });

    // Add to cart
    $('.pos-product-card').click(function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productPrice = parseFloat($(this).data('product-price'));
        const productStock = parseInt($(this).data('product-stock'));

        if (productStock <= 0) {
            alert('Product is out of stock!');
            return;
        }

        const existingItem = cart.find(item => item.product_id === productId);
        
        if (existingItem) {
            if (existingItem.quantity >= productStock) {
                alert('Cannot add more items than available stock!');
                return;
            }
            existingItem.quantity++;
            existingItem.total_price = existingItem.quantity * existingItem.unit_price;
        } else {
            cart.push({
                product_id: productId,
                name: productName,
                quantity: 1,
                unit_price: productPrice,
                total_price: productPrice
            });
        }

        updateCartDisplay();
    });

    // Update cart display
    function updateCartDisplay() {
        const cartItemsContainer = $('#cart-items');
        const emptyCart = $('#empty-cart');
        
        if (cart.length === 0) {
            emptyCart.show();
            cartItemsContainer.find('.cart-item').remove();
        } else {
            emptyCart.hide();
            cartItemsContainer.find('.cart-item').remove();
            
            cart.forEach((item, index) => {
                const cartItem = `
                    <div class="cart-item" data-index="${index}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>${item.name}</strong>
                            <button class="btn btn-sm btn-outline-danger remove-item">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-secondary decrease-qty">-</button>
                                <span class="btn btn-outline-secondary disabled">${item.quantity}</span>
                                <button class="btn btn-outline-secondary increase-qty">+</button>
                            </div>
                            <span class="fw-bold">$${item.total_price.toFixed(2)}</span>
                        </div>
                    </div>
                `;
                cartItemsContainer.append(cartItem);
            });
        }
        
        updateCartTotals();
    }

    // Cart item controls
    $(document).on('click', '.remove-item', function() {
        const index = $(this).closest('.cart-item').data('index');
        cart.splice(index, 1);
        updateCartDisplay();
    });

    $(document).on('click', '.increase-qty', function() {
        const index = $(this).closest('.cart-item').data('index');
        cart[index].quantity++;
        cart[index].total_price = cart[index].quantity * cart[index].unit_price;
        updateCartDisplay();
    });

    $(document).on('click', '.decrease-qty', function() {
        const index = $(this).closest('.cart-item').data('index');
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
            cart[index].total_price = cart[index].quantity * cart[index].unit_price;
            updateCartDisplay();
        }
    });

    // Update cart totals
    function updateCartTotals() {
        const subtotal = cart.reduce((sum, item) => sum + item.total_price, 0);
        const taxAmount = subtotal * tax_rate;
        const total = subtotal + taxAmount;

        $('#subtotal').text('$' + subtotal.toFixed(2));
        $('#tax').text('$' + taxAmount.toFixed(2));
        $('#total').text('$' + total.toFixed(2));

        // Enable/disable process sale button
        $('#process-sale').prop('disabled', cart.length === 0);
    }

    // Calculate change
    $('#paid-amount').on('input', function() {
        const total = parseFloat($('#total').text().replace('$', ''));
        const paid = parseFloat($(this).val()) || 0;
        const change = Math.max(0, paid - total);
        $('#change-amount').val('$' + change.toFixed(2));
    });

    // Clear cart
    $('#clear-cart').click(function() {
        cart = [];
        updateCartDisplay();
    });

    // Process sale
    $('#process-sale').click(function() {
        const subtotal = parseFloat($('#subtotal').text().replace('$', ''));
        const taxAmount = parseFloat($('#tax').text().replace('$', ''));
        const total = parseFloat($('#total').text().replace('$', ''));
        const paidAmount = parseFloat($('#paid-amount').val()) || 0;
        const changeAmount = parseFloat($('#change-amount').val().replace('$', '')) || 0;

        if (paidAmount < total) {
            alert('Paid amount is less than total amount!');
            return;
        }

        const saleData = {
            customer_id: $('#customer-select').val() || null,
            items: cart,
            subtotal: subtotal,
            tax_amount: taxAmount,
            total_amount: total,
            paid_amount: paidAmount,
            change_amount: changeAmount,
            payment_method: $('#payment-method').val()
        };

        // Show loading
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.post('{{ route("pos.process") }}', saleData)
            .done(function(response) {
                if (response.success) {
                    // Show success message
                    alert('Sale processed successfully!');
                    // Reset cart and form fields
                    cart = [];
                    updateCartDisplay();
                    $('#customer-select').val('');
                    $('#payment-method').val('cash');
                    $('#paid-amount').val('');
                    $('#change-amount').val('');
                } else {
                    alert(response.message || 'Failed to process sale.');
                }
            })
            .fail(function(xhr) {
                alert('Error processing sale. Please try again.');
            })
            .always(function() {
                $('#process-sale').prop('disabled', cart.length === 0)
                    .html('<i class="fas fa-check me-2"></i>Process Sale');
            });
    });
});
</script>
@endpush


