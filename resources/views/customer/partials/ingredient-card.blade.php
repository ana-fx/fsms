<!-- Product Card -->
<div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
    <!-- Product Icon -->
    <div class="flex items-center justify-center h-32 rounded mb-4"
         style="background: linear-gradient(135deg, {{ $product->foodCategory->color }}20 0%, {{ $product->foodCategory->color }}40 100%);">
        <i class="{{ $product->foodCategory->icon }} text-4xl" style="color: {{ $product->foodCategory->color }}"></i>
    </div>

    <!-- Product Info -->
    <h3 class="font-semibold text-lg mb-2 text-gray-900">{{ $product->name }}</h3>
    <p class="text-gray-600 mb-2 text-sm min-h-[40px]">{{ Str::limit($product->description, 60) }}</p>

    <!-- Category Badge -->
    <div class="mb-3">
        <span class="inline-block px-2 py-1 text-xs font-semibold rounded"
              style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">
            {{ $product->foodCategory->name }}
        </span>
    </div>

    <!-- Price and Minimum Purchase -->
    <div class="mb-3 space-y-2">
        <div class="flex justify-between items-center">
            <span class="text-green-600 font-bold text-lg">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>
            <span class="text-xs text-gray-500">/{{ $product->unit }}</span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Min. Purchase:</span>
            <span class="font-semibold text-blue-600">
                {{ $product->min_purchase }} {{ $product->unit }}
            </span>
        </div>
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

