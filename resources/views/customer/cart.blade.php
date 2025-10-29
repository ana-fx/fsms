@extends('layouts.app')

@section('title', 'Keranjang - FSMS')

@section('content')
<div class="flex bg-gray-100">
    @include('customer.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
                    <p class="text-gray-600 mt-2">Review produk yang akan dipesan</p>
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
                                                
                                                <div class="flex items-center justify-between">
                                                    <!-- Quantity Control -->
                                                    <div class="flex items-center space-x-3">
                                                        <button onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})" 
                                                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                                            <i class="fas fa-minus text-xs"></i>
                                                        </button>
                                                        <input type="number" 
                                                               id="quantity-{{ $item['product']->id }}"
                                                               value="{{ $item['quantity'] }}" 
                                                               min="0.01" 
                                                               step="0.01"
                                                               onchange="updateQuantity({{ $item['product']->id }}, this.value)"
                                                               class="w-20 text-center border border-gray-300 rounded py-1 text-sm">
                                                        <button onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})" 
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
                                        <i class="fas fa-trash-alt mr-2"></i>Kosongkan Keranjang
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
                                        <span class="font-semibold">{{ count($items) }} produk</span>
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

                                <a href="{{ route('customer.requests.create') }}" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-center block">
                                    <i class="fas fa-shopping-cart mr-2"></i>Lanjutkan ke Pemesanan
                                </a>

                                <a href="{{ route('customer.products') }}" class="w-full mt-3 bg-gray-200 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center block">
                                    <i class="fas fa-arrow-left mr-2"></i>Lanjutkan Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-shopping-cart text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Keranjang Anda kosong</h3>
                        <p class="text-gray-500 mb-6">Tambahkan produk ke keranjang untuk memulai</p>
                        <a href="{{ route('customer.products') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold inline-block">
                            <i class="fas fa-store mr-2"></i>Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateQuantity(productId, quantity) {
    if (quantity <= 0) {
        if (confirm('Hapus produk dari keranjang?')) {
            removeItem(productId);
        }
        return;
    }

    fetch(`/customer/cart/update/${productId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui keranjang');
    });
}

function removeItem(productId) {
    if (!confirm('Hapus produk dari keranjang?')) {
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
            document.getElementById(`cart-item-${productId}`).remove();
            updateCartCount();
            
            // Reload if cart is empty
            if (document.querySelectorAll('[id^="cart-item-"]').length === 0) {
                location.reload();
            } else {
                location.reload();
            }
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus produk');
    });
}

function clearCart() {
    if (!confirm('Kosongkan seluruh keranjang?')) {
        return;
    }

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
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengosongkan keranjang');
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

