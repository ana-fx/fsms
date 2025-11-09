<!-- Product Card -->
<div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
    <!-- Product Image/Icon -->
    <div class="flex items-center justify-center h-32 rounded-lg mb-4 overflow-hidden bg-gray-100">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover">
        @else
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        @endif
    </div>

    <!-- Product Info -->
    <h3 class="font-semibold text-lg mb-2 text-gray-900">{{ $product->name }}</h3>
    <p class="text-gray-600 mb-2 text-sm min-h-[40px]">{{ Str::limit($product->description, 60) }}</p>

    <!-- Category Badge -->
    <div class="mb-3">
        @if($product->foodCategory->parent)
            <div class="flex items-center gap-1 flex-wrap">
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded"
                      style="background: {{ $product->foodCategory->parent->color }}20; color: {{ $product->foodCategory->parent->color }}">
                    {{ $product->foodCategory->parent->name }}
                </span>
                <span class="text-xs text-gray-400">›</span>
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded"
                      style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">
                    {{ $product->foodCategory->name }}
                </span>
            </div>
        @else
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded"
                  style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">
                {{ $product->foodCategory->name }}
            </span>
        @endif
    </div>

    <!-- Price and Purchase Limits -->
    <div class="mb-3 space-y-2">
        <div class="flex justify-between items-center">
            <span class="text-green-600 font-bold text-lg">
                Rp {{ number_format($product->getFinalPrice(), 0, ',', '.') }}
            </span>
            <span class="text-xs text-gray-500">/{{ $product->unit }}</span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Min. Purchase:</span>
            <span class="font-semibold text-blue-600">
                {{ $product->min_purchase }} {{ $product->unit }}
            </span>
        </div>
        @if($product->max_purchase)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Max. Purchase:</span>
                <span class="font-semibold text-red-600">
                    {{ $product->max_purchase }} {{ $product->unit }}
                </span>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-2 mt-3">
        <button onclick="addToCart({{ $product->id }}, {{ $product->min_purchase }})"
                class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
            <i class="fas fa-cart-plus mr-2"></i>Add to Cart
        </button>
        <button onclick="selectProduct({{ $product->id }})"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
            <i class="fas fa-info-circle"></i>
        </button>
    </div>
</div>

