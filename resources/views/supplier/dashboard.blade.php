@extends('layouts.app')

@section('title', 'Dashboard Supplier - FSMS')

@section('content')
@php
    $ingredients = \App\Models\FoodItem::where('supplier_id', auth()->id())->with('foodCategory')->latest()->get();
    $totalIngredients = $ingredients->count();
    $activeIngredients = $ingredients->where('is_active', true)->count();

    // Get orders for this supplier's ingredients
    $ingredientIds = $ingredients->pluck('id');
    $orders = \App\Models\FoodRequest::whereIn('food_item_id', $ingredientIds)->get();

    $pendingOrders = $orders->where('status', 'pending')->count();
    $paymentPendingOrders = $orders->where('status', 'payment_pending')->count();
    $paidOrders = $orders->where('status', 'paid')->count();
    $shippingOrders = $orders->where('status', 'shipping')->count();
    $deliveredOrders = $orders->where('status', 'delivered')->count();
    $completedOrders = $orders->where('status', 'completed')->count();
    $rejectedOrders = $orders->where('status', 'rejected')->count();

    // Status distribution for chart
    $statusDistribution = [
        'pending' => $pendingOrders,
        'payment_pending' => $paymentPendingOrders,
        'paid' => $paidOrders,
        'shipping' => $shippingOrders,
        'delivered' => $deliveredOrders,
        'completed' => $completedOrders,
        'rejected' => $rejectedOrders,
    ];

    // Daily trend data (last 30 days)
    $dailyTrend = [];
    for ($i = 29; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $count = \App\Models\FoodRequest::whereIn('food_item_id', $ingredientIds)
            ->whereDate('created_at', $date->format('Y-m-d'))
            ->count();
        $dailyTrend[] = [
            'date' => $date->format('d M'),
            'count' => $count,
        ];
    }

    // Calculate total revenue (sum of paid, shipping, delivered, completed orders)
    $totalRevenue = $orders->filter(function($order) {
        return in_array($order->status, ['paid', 'shipping', 'delivered', 'completed']);
    })->sum(function($order) {
        return $order->foodItem ? $order->foodItem->price * $order->quantity : 0;
    });
@endphp
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

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
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Supplier</h1>
        <p class="text-gray-600 mt-2">Manage your ingredients and receive orders from admin</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Ingredients - Clickable -->
        <a href="{{ route('supplier.ingredients') }}" class="bg-white rounded-lg shadow p-6 card-hover hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Ingredients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalIngredients }}</p>
                </div>
            </div>
        </a>

        <!-- Active Ingredients - Clickable -->
        <a href="{{ route('supplier.ingredients') }}" class="bg-white rounded-lg shadow p-6 card-hover hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Ingredients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeIngredients }}</p>
                </div>
            </div>
        </a>

        <!-- Payment Pending Orders - Clickable -->
        <a href="{{ route('supplier.orders.index', ['status' => 'payment_pending']) }}" class="bg-white rounded-lg shadow p-6 card-hover hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Payment Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $paymentPendingOrders }}</p>
                </div>
            </div>
        </a>

        <!-- Paid Orders - Clickable -->
        <a href="{{ route('supplier.orders.index', ['status' => 'paid']) }}" class="bg-white rounded-lg shadow p-6 card-hover hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-money-bill text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Paid Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $paidOrders }}</p>
                </div>
            </div>
        </a>

        <!-- Shipping Orders - Clickable -->
        <a href="{{ route('supplier.orders.index', ['status' => 'shipping']) }}" class="bg-white rounded-lg shadow p-6 card-hover hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-truck text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Shipping</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $shippingOrders }}</p>
                </div>
            </div>
        </a>

        <!-- Delivered Orders - Clickable -->
        <a href="{{ route('supplier.orders.index', ['status' => 'delivered']) }}" class="bg-white rounded-lg shadow p-6 card-hover hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <i class="fas fa-box-check text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Delivered</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $deliveredOrders }}</p>
                </div>
            </div>
        </a>

        <!-- Completed Orders - Clickable -->
        <a href="{{ route('supplier.orders.index', ['status' => 'completed']) }}" class="bg-white rounded-lg shadow p-6 card-hover hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $completedOrders }}</p>
                </div>
            </div>
        </a>

        <!-- Total Revenue - Info Only -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
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
                    <h3 class="text-lg font-bold text-gray-900">Order Status Distribution</h3>
                    <p class="text-sm text-gray-500 mt-1">Overview of order statuses</p>
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
                    <h3 class="text-lg font-bold text-gray-900">Daily Order Trend</h3>
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
                    label: 'Jumlah Order',
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
                        display: false
                    },
                    tooltip: {
                        enabled: true,
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
                                return `${label}: ${value} orders (${percentage}%)`;
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

    // Daily Trend Bar Chart
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
                    label: 'Total Orders',
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
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                return `Orders: ${context.parsed.y}`;
                            }
                        },
                        cornerRadius: 8
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
