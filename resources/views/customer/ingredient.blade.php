@extends('layouts.app')

@section('title', 'Ingredients - FSMS')

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
                    <h1 class="text-3xl font-bold text-gray-900">Ingredients</h1>
                    <p class="text-gray-600 mt-2">Choose available ingredients</p>
                </div>

                <!-- Search and Filter Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form id="searchForm" class="flex flex-col md:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text"
                                       id="searchInput"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search ingredients..."
                                       autocomplete="off"
                                       class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                <div id="searchLoading" class="hidden absolute right-3 top-3">
                                    <i class="fas fa-spinner fa-spin text-green-600"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="w-full md:w-64">
                            <select id="categorySelect"
                                    name="category"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Clear Button -->
                        <button type="button" 
                                id="clearBtn"
                                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors font-semibold {{ !request('search') && !request('category') ? 'hidden' : '' }}">
                            <i class="fas fa-times mr-2"></i>Reset
                        </button>
                    </form>
                </div>

                <!-- Products Grid -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div id="productsContainer">
                        @if($products->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($products as $product)
                                    @include('customer.partials.ingredient-card', ['product' => $product])
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div id="paginationContainer" class="mt-6">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <i class="fas fa-box-open text-5xl text-gray-400 mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-900 mb-2">No ingredients found</h3>
                                <p class="text-gray-500 mb-6" id="emptyMessage">
                                    @if(request('search') || request('category'))
                                        Try different keywords or filters
                                    @else
                                        No ingredients available yet
                                    @endif
                                </p>
                                @if(request('search') || request('category'))
                                    <button onclick="clearSearch()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                        <i class="fas fa-arrow-left mr-2"></i>View All Ingredients
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function selectProduct(productId) {
    // Redirect to create food request with selected product
    window.location.href = '{{ route("customer.requests.create") }}?product_id=' + productId;
}

function addToCart(productId) {
    // Get default quantity (1)
    const quantity = 1;
    
    fetch('{{ route("customer.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2';
            notification.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${data.message}</span>
            `;
            document.body.appendChild(notification);
            
            // Update cart count
            updateCartCount();
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding to the cart');
    });
}

function updateCartCount() {
    fetch('{{ route("customer.cart.count") }}')
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
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// Load cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

// Dynamic Search with AJAX
(function() {
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const clearBtn = document.getElementById('clearBtn');
    const productsContainer = document.getElementById('productsContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const searchLoading = document.getElementById('searchLoading');
    
    function performSearch() {
        const search = searchInput.value.trim();
        const category = categorySelect.value;
        
        // Show/hide clear button
        if (search || category) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
        
        // Show loading
        if (searchLoading) {
            searchLoading.classList.remove('hidden');
        }
        
        // Prepare URL
        const url = new URL('{{ route("customer.ingredients") }}', window.location.origin);
        if (search) url.searchParams.set('search', search);
        if (category) url.searchParams.set('category', category);
        url.searchParams.set('ajax', '1');
        
        // Fetch results
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update products grid
            productsContainer.innerHTML = data.html;
            
            // Reattach pagination click handlers for AJAX
            attachPaginationHandlers();
            
            // Hide loading
            if (searchLoading) {
                searchLoading.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            if (searchLoading) {
                searchLoading.classList.add('hidden');
            }
        });
    }
    
    // Search input with debounce
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 500); // Wait 500ms after user stops typing
        });
        
        // Also trigger on Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                performSearch();
            }
        });
    }
    
    // Category filter
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            performSearch();
        });
    }
    
    // Clear button
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            clearSearch();
        });
    }
    
    // Prevent form submission
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch();
        });
    }
    
    // Attach pagination handlers for AJAX pagination
    function attachPaginationHandlers() {
        const paginationContainer = document.getElementById('paginationContainer');
        if (!paginationContainer) return;
        
        const paginationLinks = paginationContainer.querySelectorAll('a[href]');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(link.href);
                url.searchParams.set('ajax', '1');
                
                // Preserve current search params
                const currentSearch = searchInput.value.trim();
                const currentCategory = categorySelect.value;
                if (currentSearch) url.searchParams.set('search', currentSearch);
                if (currentCategory) url.searchParams.set('category', currentCategory);
                
                if (searchLoading) {
                    searchLoading.classList.remove('hidden');
                }
                
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    productsContainer.innerHTML = data.html;
                    attachPaginationHandlers(); // Reattach handlers for new links
                    if (searchLoading) {
                        searchLoading.classList.add('hidden');
                    }
                    
                    // Scroll to top of products
                    productsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => {
                    console.error('Pagination error:', error);
                    if (searchLoading) {
                        searchLoading.classList.add('hidden');
                    }
                });
            });
        });
    }
    
    // Initial attachment
    attachPaginationHandlers();
    
    // Make performSearch available globally for clearSearch
    window.performSearch = performSearch;
})();

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categorySelect').value = '';
    document.getElementById('clearBtn').classList.add('hidden');
    if (window.performSearch) {
        window.performSearch();
    }
}
</script>
@endsection

