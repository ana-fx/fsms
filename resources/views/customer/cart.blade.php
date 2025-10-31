@extends('layouts.app')

@section('title', 'Cart - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('customer.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
                    <p class="text-gray-600 mt-2">Review items before checkout</p>
                </div>

                @if(count($items) > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Cart Items -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="space-y-4">
                                    @foreach($items as $index => $item)
                                        <div class="flex items-start border-b border-gray-200 pb-4 last:border-0" id="cart-item-{{ $item['product']->id }}">
                                            <!-- Product Icon -->
                                            <div class="flex items-center justify-center w-20 h-20 rounded-lg mr-4 flex-shrink-0"
                                                 style="background: linear-gradient(135deg, {{ $item['product']->foodCategory->color }}20 0%, {{ $item['product']->foodCategory->color }}40 100%);">
                                                <i class="{{ $item['product']->foodCategory->icon }} text-2xl" style="color: {{ $item['product']->foodCategory->color }}"></i>
                                            </div>

                                            <!-- Product Info -->
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ $item['product']->name }}</h3>
                                                <p class="text-sm text-gray-500 mb-2">{{ Str::limit($item['product']->description, 50) }}</p>
                                                <p class="text-xs text-blue-600 mb-2">
                                                    <i class="fas fa-info-circle mr-1"></i>Min. Purchase: {{ $item['product']->min_purchase }} {{ $item['product']->unit }}
                                                </p>
                                                
                                                <div class="flex items-center justify-between">
                                                    <!-- Quantity Control -->
                                                    <div class="flex items-center space-x-3">
                                                        <button onclick="decrementQuantity({{ $item['product']->id }}, {{ $item['quantity'] }}, {{ $item['product']->min_purchase }})" 
                                                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition-colors {{ $item['quantity'] <= $item['product']->min_purchase ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                {{ $item['quantity'] <= $item['product']->min_purchase ? 'disabled' : '' }}>
                                                            <i class="fas fa-minus text-xs"></i>
                                                        </button>
                                                        <input type="number" 
                                                               id="quantity-{{ $item['product']->id }}"
                                                               value="{{ $item['quantity'] }}" 
                                                               min="{{ $item['product']->min_purchase }}" 
                                                               step="0.01"
                                                               onchange="updateQuantity({{ $item['product']->id }}, this.value, {{ $item['product']->min_purchase }})"
                                                               onblur="validateQuantity({{ $item['product']->id }}, this.value, {{ $item['product']->min_purchase }})"
                                                               class="w-20 text-center border border-gray-300 rounded py-1 text-sm">
                                                        <button onclick="incrementQuantity({{ $item['product']->id }}, {{ $item['quantity'] }}, {{ $item['product']->min_purchase }})" 
                                                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                                            <i class="fas fa-plus text-xs"></i>
                                                        </button>
                                                        <span class="text-sm text-gray-500">{{ $item['product']->unit }}</span>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="text-right">
                                                        <p class="text-green-600 font-bold text-lg">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                                        <p class="text-xs text-gray-500">Rp {{ number_format($item['product']->price, 0, ',', '.') }} / {{ $item['product']->unit }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Remove Button -->
                                            <button onclick="removeItem({{ $item['product']->id }})" 
                                                    class="ml-4 text-red-600 hover:text-red-800 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Clear Cart Button -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <button onclick="clearCart()" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        <i class="fas fa-trash-alt mr-2"></i>Clear Cart
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan</h2>
                                
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total Item:</span>
                                        <span class="font-semibold">{{ count($items) }} items</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total Kuantitas:</span>
                                        <span class="font-semibold">{{ array_sum(array_column($items, 'quantity')) }} unit</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4 mb-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                                        <span class="text-2xl font-bold text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('customer.requests.checkout') }}" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-center block">
                                    <i class="fas fa-shopping-cart mr-2"></i>Continue to Checkout
                                </a>

                                <a href="{{ route('customer.ingredients') }}" class="w-full mt-3 bg-gray-200 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center block">
                                    <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-shopping-cart text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Your cart is empty</h3>
                        <p class="text-gray-500 mb-6">Add ingredients to your cart to get started</p>
                        <a href="{{ route('customer.ingredients') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold inline-block">
                            <i class="fas fa-store mr-2"></i>Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Confirm Action</h3>
            </div>
            <p id="confirmMessage" class="text-gray-600 mb-6"></p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeConfirmModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button id="confirmButton" onclick="executeConfirmAction()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let confirmCallback = null;

function showNotification(message, type = 'success') {
    const colors = {
        success: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800', icon: 'fa-check-circle', iconColor: 'text-green-600' },
        error: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-800', icon: 'fa-exclamation-circle', iconColor: 'text-red-600' },
        warning: { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'fa-exclamation-triangle', iconColor: 'text-yellow-600' },
        info: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-800', icon: 'fa-info-circle', iconColor: 'text-blue-600' }
    };
    
    const color = colors[type] || colors.success;
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${color.bg} ${color.border} border rounded-lg shadow-lg z-50 flex items-center space-x-3 p-4 animate-slide-in`;
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <div class="flex-shrink-0">
            <i class="fas ${color.icon} ${color.iconColor} text-xl"></i>
        </div>
        <div class="flex-1">
            <p class="${color.text} font-medium">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 ${color.text} hover:opacity-70 transition-opacity">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slide-out 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function showConfirmModal(message, callback) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmMessage').textContent = message;
    confirmCallback = callback;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    confirmCallback = null;
}

function executeConfirmAction() {
    if (confirmCallback) {
        confirmCallback();
    }
    closeConfirmModal();
}

// Close modal when clicking outside
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});
</script>
<style>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>
<script>
function decrementQuantity(productId, currentQuantity, minPurchase) {
    const newQuantity = Math.max(minPurchase, currentQuantity - 1);
    if (newQuantity < currentQuantity) {
        updateQuantity(productId, newQuantity, minPurchase);
    }
}

function incrementQuantity(productId, currentQuantity, minPurchase) {
    const newQuantity = currentQuantity + 1;
    updateQuantity(productId, newQuantity, minPurchase);
}

function validateQuantity(productId, quantity, minPurchase) {
    const qty = parseFloat(quantity) || 0;
    if (qty > 0 && qty < minPurchase) {
        showNotification(`Minimum purchase is ${minPurchase}. Setting to minimum.`, 'warning');
        document.getElementById(`quantity-${productId}`).value = minPurchase;
        updateQuantity(productId, minPurchase, minPurchase);
    }
}

function updateQuantity(productId, quantity, minPurchase = 1) {
    const qty = parseFloat(quantity) || 0;
    
    // Ensure quantity is at least min_purchase
    if (qty > 0 && qty < minPurchase) {
        showNotification(`Minimum purchase is ${minPurchase}. Please enter at least ${minPurchase}.`, 'warning');
        // Reset input to minimum
        document.getElementById(`quantity-${productId}`).value = minPurchase;
        updateQuantity(productId, minPurchase, minPurchase);
        return;
    }

    if (qty <= 0) {
        showConfirmModal('Remove item from cart?', function() {
            removeItem(productId, false);
        });
        return;
    }

    fetch(`/customer/cart/update/${productId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ quantity: qty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Cart updated successfully', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showNotification(data.message || 'An error occurred', 'error');
            // Reset to previous value if validation fails
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating the cart', 'error');
        location.reload();
    });
}

function removeItem(productId, showConfirm = true) {
    if (showConfirm) {
        showConfirmModal('Remove item from cart?', function() {
            removeItem(productId, false);
        });
        return;
    }

    fetch(`/customer/cart/remove/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Item removed from cart', 'success');
            document.getElementById(`cart-item-${productId}`).remove();
            updateCartCount();
            
            // Reload if cart is empty
            setTimeout(() => {
                if (document.querySelectorAll('[id^="cart-item-"]').length === 0) {
                    location.reload();
                } else {
                    location.reload();
                }
            }, 500);
        } else {
            showNotification(data.message || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while removing the item', 'error');
    });
}

function clearCart() {
    showConfirmModal('Clear entire cart? This action cannot be undone.', function() {
        fetch('/customer/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Cart cleared successfully', 'success');
                setTimeout(() => location.reload(), 500);
            } else {
                showNotification(data.message || 'An error occurred', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while clearing the cart', 'error');
        });
    });
}

function updateCartCount() {
    fetch('/customer/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.getElementById('cart-badge');
            if (cartBadge) {
                if (data.count > 0) {
                    cartBadge.textContent = data.count;
                    cartBadge.classList.remove('hidden');
                } else {
                    cartBadge.classList.add('hidden');
                }
            }
        });
}
</script>
@endpush

