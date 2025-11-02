@extends('layouts.app')

@section('title', 'Dashboard - FSMS')

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
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                        <p class="mt-2 text-gray-600">Welcome back! Here's your overview and quick access to all features</p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-gray-100 rounded-lg">
                                <i class="fas fa-list text-gray-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Requests</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Payment Pending</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['payment_pending'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Paid</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['paid'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-truck text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Shipping</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['shipping'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-indigo-100 rounded-lg">
                                <i class="fas fa-box-check text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Delivered</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['delivered'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Completed</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Status Distribution Donut Chart -->
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Status Distribution</h3>
                                <p class="text-sm text-gray-500 mt-1">Overview of request statuses</p>
                            </div>
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="fas fa-chart-pie text-green-600"></i>
                            </div>
                        </div>
                        <div class="relative" style="height: 320px;">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                    </div>

                    <!-- Daily Trend Bar Chart -->
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Daily Request Trend</h3>
                                <p class="text-sm text-gray-500 mt-1">Last 30 days activity</p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="fas fa-chart-bar text-blue-600"></i>
                            </div>
                        </div>
                        <div class="relative" style="height: 320px;">
                            <canvas id="dailyTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Widget Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Cart Widget -->
                    <a href="{{ route('customer.cart') }}" class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-purple-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-shopping-cart text-white text-2xl"></i>
                                </div>
                                @if($cartCount > 0)
                                    <span class="flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-full text-sm font-bold shadow-lg animate-pulse">{{ $cartCount }}</span>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Shopping Cart</h3>
                                <p class="text-gray-500 text-sm">
                                    {{ $cartCount > 0 ? $cartCount . ' item(s) in cart' : 'Cart is empty' }}
                                </p>
                                @if($cartTotal > 0)
                                    <div class="pt-2">
                                        <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Total amount</p>
                                    </div>
                                @else
                                    <p class="text-sm text-purple-600 font-medium mt-2">Start shopping now →</p>
                                @endif
                            </div>
                        </div>
                    </a>

                    <!-- Ingredients Widget -->
                    <a href="{{ route('customer.ingredients') }}" class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 bg-gradient-to-br from-green-50 via-green-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-box text-white text-2xl"></i>
                                </div>
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">Browse Ingredients</h3>
                                <p class="text-gray-500 text-sm">Explore our catalog</p>
                                <div class="pt-2">
                                    <div class="flex items-center text-green-600 font-semibold text-sm">
                                        <span>Shop now</span>
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Delivery Addresses Widget -->
                    <a href="{{ route('customer.settings.delivery-addresses') }}" class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                                </div>
                                @if($addressesCount > 0)
                                    <span class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full text-sm font-bold shadow-lg">{{ $addressesCount }}</span>
                                @else
                                    <div class="w-10 h-10 flex items-center justify-center bg-yellow-100 rounded-full">
                                        <i class="fas fa-exclamation text-yellow-600 text-sm"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Delivery Addresses</h3>
                                <p class="text-gray-500 text-sm">
                                    {{ $addressesCount > 0 ? $addressesCount . ' address(es) saved' : 'No addresses yet' }}
                                </p>
                                <div class="pt-2">
                                    <div class="flex items-center text-blue-600 font-semibold text-sm">
                                        <span>{{ $addressesCount > 0 ? 'Manage' : 'Add address' }}</span>
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Payment Pending Widget -->
                    @if($stats['payment_pending'] > 0)
                        <a href="{{ route('customer.requests.index') }}?status=payment_pending" class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-orange-200">
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 via-red-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <div class="relative p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="p-4 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-money-check-alt text-white text-2xl"></i>
                                    </div>
                                    <span class="flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-full text-sm font-bold shadow-lg animate-pulse">{{ $stats['payment_pending'] }}</span>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors">Payment Pending</h3>
                                    <p class="text-gray-500 text-sm">Upload payment proof</p>
                                    <div class="pt-2">
                                        <div class="flex items-center text-orange-600 font-semibold text-sm">
                                            <span>View requests</span>
                                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="relative bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-transparent"></div>
                            
                            <div class="relative p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="p-4 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl shadow-lg">
                                        <i class="fas fa-check-circle text-white text-2xl"></i>
                                    </div>
                                    <div class="w-10 h-10 flex items-center justify-center bg-green-100 rounded-full">
                                        <i class="fas fa-check text-green-600"></i>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-lg font-bold text-gray-900">All Clear</h3>
                                    <p class="text-gray-500 text-sm">No pending payments</p>
                                    <div class="pt-2">
                                        <p class="text-sm text-gray-600 font-medium">You're all set! ✨</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('customer.ingredients') }}" class="group relative flex items-center p-4 rounded-xl border-2 border-gray-100 hover:border-green-300 hover:bg-green-50 transition-all duration-300">
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 group-hover:bg-green-500 rounded-xl mr-4 transition-colors duration-300">
                                <i class="fas fa-shopping-bag text-green-600 group-hover:text-white text-lg transition-colors"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-semibold text-gray-900 group-hover:text-green-700 transition-colors">Shop Ingredients</h4>
                                <p class="text-sm text-gray-500">Browse and add to cart</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="{{ route('customer.requests.index') }}" class="group relative flex items-center p-4 rounded-xl border-2 border-gray-100 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 group-hover:bg-blue-500 rounded-xl mr-4 transition-colors duration-300">
                                <i class="fas fa-list-alt text-blue-600 group-hover:text-white text-lg transition-colors"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">View Requests</h4>
                                <p class="text-sm text-gray-500">Manage all requests</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="{{ route('customer.settings.account') }}" class="group relative flex items-center p-4 rounded-xl border-2 border-gray-100 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300">
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 group-hover:bg-purple-500 rounded-xl mr-4 transition-colors duration-300">
                                <i class="fas fa-cog text-purple-600 group-hover:text-white text-lg transition-colors"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-semibold text-gray-900 group-hover:text-purple-700 transition-colors">Settings</h4>
                                <p class="text-sm text-gray-500">Account & preferences</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Pie Chart
    const statusCtx = document.getElementById('statusPieChart');
    if (statusCtx) {
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Payment Pending', 'Paid', 'Shipping', 'Delivered', 'Completed', 'Rejected'],
                datasets: [{
                    label: 'Jumlah Request',
                    data: [
                        {{ $statusDistribution['pending'] }},
                        {{ $statusDistribution['payment_pending'] }},
                        {{ $statusDistribution['paid'] }},
                        {{ $statusDistribution['shipping'] }},
                        {{ $statusDistribution['delivered'] }},
                        {{ $statusDistribution['completed'] }},
                        {{ $statusDistribution['rejected'] }}
                    ],
                    backgroundColor: [
                        'rgba(107, 114, 128, 0.8)', // gray - pending
                        'rgba(251, 191, 36, 0.8)',  // yellow - payment_pending
                        'rgba(34, 197, 94, 0.8)',  // green - paid
                        'rgba(59, 130, 246, 0.8)', // blue - shipping
                        'rgba(99, 102, 241, 0.8)', // indigo - delivered
                        'rgba(168, 85, 247, 0.8)', // purple - completed
                        'rgba(239, 68, 68, 0.8)'   // red - rejected
                    ],
                    borderColor: [
                        '#6b7280',
                        '#fbbf24',
                        '#22c55e',
                        '#3b82f6',
                        '#6366f1',
                        '#a855f7',
                        '#ef4444'
                    ],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 18,
                            font: {
                                size: 12,
                                weight: '600',
                                family: 'Inter'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 12,
                            boxHeight: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        padding: 14,
                        titleFont: {
                            size: 14,
                            weight: 'bold',
                            family: 'Inter'
                        },
                        bodyFont: {
                            size: 13,
                            family: 'Inter'
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} requests (${percentage}%)`;
                            }
                        },
                        displayColors: true,
                        cornerRadius: 8,
                        caretSize: 6
                    }
                },
                cutout: '65%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    // Daily Trend Bar Chart (Modern Gradient Design)
    const trendCtx = document.getElementById('dailyTrendChart');
    if (trendCtx) {
        const dailyData = @json($dailyTrend);
        
        // Create gradient for bars
        const ctx = trendCtx.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradient.addColorStop(0.5, 'rgba(34, 197, 94, 0.6)');
        gradient.addColorStop(1, 'rgba(168, 85, 247, 0.4)');
        
        const trendChart = new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: dailyData.map(item => item.date),
                datasets: [{
                    label: 'Total Requests',
                    data: dailyData.map(item => item.count),
                    backgroundColor: gradient,
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        padding: 14,
                        titleFont: {
                            size: 14,
                            weight: 'bold',
                            family: 'Inter'
                        },
                        bodyFont: {
                            size: 13,
                            family: 'Inter'
                        },
                        callbacks: {
                            label: function(context) {
                                return `📊 Requests: ${context.parsed.y}`;
                            },
                            title: function(context) {
                                return `📅 ${context[0].label}`;
                            }
                        },
                        displayColors: false,
                        cornerRadius: 8,
                        caretSize: 6
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#6b7280',
                            padding: 8
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.06)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 9,
                                weight: '500'
                            },
                            color: '#6b7280',
                            maxTicksLimit: 15
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
});
</script>
@endpush
@endsection

