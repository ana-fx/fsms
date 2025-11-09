@extends('layouts.app')

@section('title', 'Ingredients - FSMS')

@section('content')

<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('admin.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="flex-1 bg-gray-100 min-h-screen">
            <div class="w-full py-8">
                <!-- Header -->
                <div class="mb-8 px-4 sm:px-6 lg:px-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Ingredients</h1>
                        <p class="mt-2 text-gray-600">Manage ingredients and set maximum price limits</p>
                    </div>
                </div>

                <!-- Ingredients List -->
                <div class="bg-white rounded-lg shadow mx-4 sm:mx-6 lg:mx-8">
                    <!-- Filter Section inside table container -->
                    <div class="px-4 sm:px-6 py-3 border-b border-gray-200">
                        <form method="GET" action="{{ route('admin.max-price') }}" class="flex flex-wrap items-end gap-3">
                            <!-- Search -->
                            <div class="flex-1 min-w-[200px]">
                                <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name..." class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Category Filter -->
                            <div class="flex-1 min-w-[150px]">
                                <label for="category" class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                                <select name="category" id="category" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Supplier Filter -->
                            <div class="flex-1 min-w-[150px]">
                                <label for="supplier" class="block text-xs font-medium text-gray-600 mb-1">Supplier</label>
                                <select name="supplier" id="supplier" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="flex-1 min-w-[120px]">
                                <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status" id="status" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <!-- Stock Filter -->
                            <div class="flex-1 min-w-[130px]">
                                <label for="stock" class="block text-xs font-medium text-gray-600 mb-1">Stock</label>
                                <select name="stock" id="stock" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Stock</option>
                                    <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock (≤10)</option>
                                    <option value="in_stock" {{ request('stock') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="out_of_stock" {{ request('stock') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-filter mr-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.max-price') }}" class="px-3 py-1.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm" title="Reset">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    @if($products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ingredient Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Supplier</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Category</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Price/Unit</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Maximum Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Price Increment</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Stock</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Min Purchase</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover mr-3">
                                                    @else
                                                        <div class="h-10 w-10 rounded-lg mr-3 flex items-center justify-center" style="background: {{ $product->foodCategory->color }}20">
                                                            <i class="{{ $product->foodCategory->icon }}" style="color: {{ $product->foodCategory->color }}"></i>
                                                        </div>
                                                    @endif
                                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->supplier->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $product->supplier->email ?? '' }}</div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full" style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">
                                                    <i class="{{ $product->foodCategory->icon }} mr-1"></i>
                                                    {{ $product->foodCategory->name }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}/{{ $product->unit }}</div>
                                                @if($product->max_price && $product->price > $product->max_price)
                                                    <div class="text-xs text-red-600 font-medium mt-0.5">
                                                        <i class="fas fa-exclamation-triangle"></i> Exceeded!
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <input type="number"
                                                           id="max-price-{{ $product->id }}"
                                                           value="{{ $product->max_price ?? '' }}"
                                                           min="0"
                                                           step="0.01"
                                                           placeholder="No limit"
                                                           data-current-price="{{ $product->price }}"
                                                           class="w-24 px-2 py-1 text-xs border {{ $product->max_price && $product->price > $product->max_price ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                                           onchange="updateMaxPrice({{ $product->id }}, {{ $product->price }})"
                                                           onblur="validateMaxPrice({{ $product->id }}, {{ $product->price }})">
                                                    @if($product->max_price)
                                                        <button type="button"
                                                                onclick="clearMaxPrice({{ $product->id }})"
                                                                class="px-2 py-1 text-xs text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors"
                                                                title="Clear maximum price">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                @if($product->max_price)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Limit: Rp {{ number_format($product->max_price, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <input type="number"
                                                           id="price-increment-{{ $product->id }}"
                                                           value="{{ $product->default_price_increment ?? 500 }}"
                                                           min="0"
                                                           step="{{ ($product->price_increment_type ?? 'fixed') === 'percentage' ? '0.01' : '1' }}"
                                                           {{ ($product->price_increment_type ?? 'fixed') === 'percentage' ? 'max="100"' : '' }}
                                                           class="w-20 px-2 py-1 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                                           onchange="updatePriceIncrement({{ $product->id }})"
                                                           onblur="validatePriceIncrement({{ $product->id }})">
                                                    <select id="increment-type-{{ $product->id }}"
                                                            class="px-2 py-1 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                                            onchange="handleTypeChange({{ $product->id }})">
                                                        <option value="fixed" {{ ($product->price_increment_type ?? 'fixed') === 'fixed' ? 'selected' : '' }}>Rp</option>
                                                        <option value="percentage" {{ ($product->price_increment_type ?? 'fixed') === 'percentage' ? 'selected' : '' }}>%</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}</div>
                                                @if($product->stock <= 10)
                                                    <span class="text-xs text-red-600 font-medium">Low Stock</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($product->min_purchase, 0, ',', '.') }} {{ $product->unit }}</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($product->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $product->created_at->format('d M Y') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No ingredients found</h3>
                            <p class="text-gray-500">There are no ingredients matching your criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validatePriceIncrement(ingredientId) {
    const input = document.getElementById('price-increment-' + ingredientId);
    const select = document.getElementById('increment-type-' + ingredientId);
    const value = parseFloat(input.value);
    const type = select.value;

    if (isNaN(value) || value < 0) {
        input.value = input.defaultValue || 500;
        return;
    }

    if (type === 'percentage' && value > 100) {
        input.value = 100;
        showNotification('Percentage cannot exceed 100%', 'error');
    }
}

function handleTypeChange(ingredientId) {
    const input = document.getElementById('price-increment-' + ingredientId);
    const select = document.getElementById('increment-type-' + ingredientId);
    const type = select.value;

    // Update input attributes based on type
    if (type === 'percentage') {
        input.step = '0.01';
        input.max = '100';
        // Validate current value
        if (parseFloat(input.value) > 100) {
            input.value = 100;
        }
    } else {
        input.step = '1';
        input.removeAttribute('max');
    }

    // Update price increment
    updatePriceIncrement(ingredientId);
}

function updatePriceIncrement(ingredientId) {
    // Get current values
    const input = document.getElementById('price-increment-' + ingredientId);
    const select = document.getElementById('increment-type-' + ingredientId);
    const originalValue = input.value;
    const originalType = select.value;
    const currentType = select.value;
    const currentValue = input.value;

    // Validate value
    if (!currentValue || currentValue < 0) {
        input.value = originalValue;
        return;
    }

    // Validate percentage
    if (currentType === 'percentage' && parseFloat(currentValue) > 100) {
        input.value = 100;
        showNotification('Percentage cannot exceed 100%', 'error');
        return;
    }

    // Disable inputs during request
    input.disabled = true;
    input.classList.add('opacity-50');
    if (select) {
        select.disabled = true;
        select.classList.add('opacity-50');
    }

    fetch(`/admin/ingredients/${ingredientId}/update-price-increment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            default_price_increment: parseFloat(currentValue),
            price_increment_type: currentType
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to update price increment');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification(data.message || 'Price increment updated successfully', 'success');
            input.value = data.default_price_increment;
            input.defaultValue = data.default_price_increment;
            // Update step based on type
            if (data.price_increment_type === 'percentage') {
                input.step = '0.01';
                input.max = '100'; // Percentage should not exceed 100
            } else {
                input.step = '1';
                input.removeAttribute('max'); // Remove max for fixed amount
            }
        } else {
            // Revert on error
            input.value = originalValue;
            if (select) {
                select.value = originalType;
            }
            showNotification(data.message || 'Failed to update price increment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        input.value = originalValue;
        if (select) {
            select.value = originalType;
        }
        showNotification(error.message || 'An error occurred while updating price increment', 'error');
    })
    .finally(() => {
        // Re-enable inputs
        input.disabled = false;
        input.classList.remove('opacity-50');
        if (select) {
            select.disabled = false;
            select.classList.remove('opacity-50');
        }
    });
}

function validateMaxPrice(ingredientId, currentPrice) {
    const input = document.getElementById('max-price-' + ingredientId);
    const value = parseFloat(input.value);

    if (input.value === '' || input.value === null) {
        // Allow empty value (no limit)
        return;
    }

    if (isNaN(value) || value < 0) {
        input.value = '';
        return;
    }

    if (value > 0 && currentPrice > value) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-300');
    } else {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
    }
}

function updateMaxPrice(ingredientId, currentPrice) {
    const input = document.getElementById('max-price-' + ingredientId);
    const value = input.value === '' ? null : parseFloat(input.value);

    // Validate value
    if (value !== null && (isNaN(value) || value < 0)) {
        input.value = '';
        return;
    }

    // Validate price <= max_price if max_price is set
    if (value !== null && value > 0 && currentPrice > value) {
        showNotification('Current price cannot exceed maximum price limit!', 'error');
        input.focus();
        return;
    }

    // Disable input during request
    input.disabled = true;
    input.classList.add('opacity-50');

    fetch(`/admin/ingredients/${ingredientId}/update-max-price`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            max_price: value
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to update maximum price');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Maximum price updated successfully', 'success');
            const container = input.parentElement.parentElement;

            if (data.max_price) {
                input.value = parseFloat(data.max_price);
                // Update border color based on validation
                if (data.price > data.max_price) {
                    input.classList.add('border-red-500');
                    input.classList.remove('border-gray-300');
                } else {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-gray-300');
                }

                // Show clear button if not exists
                let clearBtn = input.parentElement.querySelector('button[onclick*="clearMaxPrice"]');
                if (!clearBtn) {
                    clearBtn = document.createElement('button');
                    clearBtn.type = 'button';
                    clearBtn.onclick = function() { clearMaxPrice(ingredientId); };
                    clearBtn.className = 'px-2 py-1 text-xs text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors';
                    clearBtn.title = 'Clear maximum price';
                    clearBtn.innerHTML = '<i class="fas fa-times"></i>';
                    input.parentElement.appendChild(clearBtn);
                } else {
                    clearBtn.style.display = 'block';
                }

                // Update or create limit display
                let limitDisplay = container.querySelector('.text-xs.text-gray-500');
                if (!limitDisplay) {
                    limitDisplay = document.createElement('div');
                    limitDisplay.className = 'text-xs text-gray-500 mt-1';
                    container.appendChild(limitDisplay);
                }
                limitDisplay.textContent = 'Limit: Rp ' + parseFloat(data.max_price).toLocaleString('id-ID');
            } else {
                input.value = '';
                input.classList.remove('border-red-500');
                input.classList.add('border-gray-300');

                // Hide the clear button
                const clearBtn = input.parentElement.querySelector('button[onclick*="clearMaxPrice"]');
                if (clearBtn) {
                    clearBtn.style.display = 'none';
                }

                // Remove the limit display
                const limitDisplay = container.querySelector('.text-xs.text-gray-500');
                if (limitDisplay) {
                    limitDisplay.remove();
                }
            }

            // Update the "Exceeded!" warning in Price/Unit column
            const priceCell = input.closest('tr').querySelector('td:nth-child(4)');
            const exceededWarning = priceCell.querySelector('.text-red-600');
            if (data.max_price && data.price > data.max_price) {
                if (!exceededWarning) {
                    const warning = document.createElement('div');
                    warning.className = 'text-xs text-red-600 font-medium mt-0.5';
                    warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Exceeded!';
                    priceCell.appendChild(warning);
                }
            } else {
                if (exceededWarning) {
                    exceededWarning.remove();
                }
            }
        } else {
            showNotification(data.message || 'Failed to update maximum price', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'An error occurred while updating maximum price', 'error');
    })
    .finally(() => {
        // Re-enable input
        input.disabled = false;
        input.classList.remove('opacity-50');
    });
}

function clearMaxPrice(ingredientId) {
    if (!confirm('Are you sure you want to remove the maximum price limit?')) {
        return;
    }
    const input = document.getElementById('max-price-' + ingredientId);
    const currentPrice = parseFloat(input.getAttribute('data-current-price')) || 0;
    input.value = '';
    updateMaxPrice(ingredientId, currentPrice);
}

function showNotification(message, type) {
    const colors = {
        success: { bg: 'bg-green-100', border: 'border-green-500', text: 'text-green-700', icon: 'fa-check-circle' },
        error: { bg: 'bg-red-100', border: 'border-red-500', text: 'text-red-700', icon: 'fa-exclamation-circle' }
    };

    const color = colors[type] || colors.success;

    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${color.bg} ${color.border} border-l-4 ${color.text} p-4 rounded-lg shadow-lg z-50 max-w-md`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${color.icon} mr-3"></i>
            <p class="font-medium">${message}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 ${color.text} hover:opacity-70">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transition = 'opacity 0.3s ease-out';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection


