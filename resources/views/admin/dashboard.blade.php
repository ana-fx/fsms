@extends('layouts.app')

@section('title', 'Dashboard Super Admin - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('admin.partials.sidebar')

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
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Super Admin</h1>
                    <p class="mt-2 text-gray-600">Manage the entire food supply system and set maximum prices</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Requests</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalRequests }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $regularRequests }} Regular, {{ $customRequests }} Custom
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Suppliers -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-truck text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Active Suppliers</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalSuppliers }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $activeIngredients }}/{{ $totalIngredients }} Active Items
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Registered Customers -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Registered Customers</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Active Users
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    All Time
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Status Distribution Donut Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
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
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
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

                <!-- Revenue Chart -->
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Monthly Revenue</h3>
                            <p class="text-sm text-gray-500 mt-1">Revenue trend over last 6 months</p>
                        </div>
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <i class="fas fa-chart-line text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="relative" style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Bottom Section: Recent Requests and Top Suppliers -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Pending Custom Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pending Custom Requests</h3>
                                <p class="text-sm text-gray-500 mt-1">Requests waiting for approval</p>
                            </div>
                            <a href="{{ route('admin.custom-requests.index', ['status' => 'pending']) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        @if($pendingCustomRequests->count() > 0)
                            <div class="space-y-4">
                                @foreach($pendingCustomRequests as $request)
                                    <a href="{{ route('admin.custom-requests.show', $request) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border-l-4 border-yellow-400">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900">{{ $request->title }}</p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span class="font-medium">{{ $request->customer->name }}</span> • 
                                                    {{ $request->quantity }} {{ $request->unit }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                            </div>
                                            <div class="ml-4">
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No pending requests</h3>
                                <p class="text-gray-500">All custom requests have been processed</p>
                            </div>
                        @endif
                    </div>

                    <!-- Top Suppliers -->
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Top Suppliers</h3>
                                <p class="text-sm text-gray-500 mt-1">Most active suppliers</p>
                            </div>
                            <a href="{{ route('admin.users') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        @if($topSuppliers->count() > 0)
                            <div class="space-y-4">
                                @foreach($topSuppliers as $index => $supplier)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center flex-1">
                                            <div class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-700 rounded-full font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <p class="text-sm font-semibold text-gray-900">{{ $supplier->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $supplier->email }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-gray-900">{{ $supplier->total_orders ?? 0 }}</p>
                                            <p class="text-xs text-gray-500">Orders</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-store text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No suppliers yet</h3>
                                <p class="text-gray-500">Suppliers will appear here when they add items</p>
                            </div>
                        @endif
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
                labels: ['Pending', 'Payment Pending', 'Paid', 'Rejected'],
                datasets: [{
                    label: 'Number of Requests',
                    data: [
                        {{ $statusDistribution['pending'] }},
                        {{ $statusDistribution['payment_pending'] }},
                        {{ $statusDistribution['paid'] }},
                        {{ $statusDistribution['rejected'] }}
                    ],
                    backgroundColor: [
                        'rgba(107, 114, 128, 0.8)', // gray - pending
                        'rgba(251, 191, 36, 0.8)',  // yellow - payment_pending
                        'rgba(34, 197, 94, 0.8)',  // green - paid
                        'rgba(239, 68, 68, 0.8)'   // red - rejected
                    ],
                    borderColor: [
                        '#6b7280',
                        '#fbbf24',
                        '#22c55e',
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
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed + ' requests';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Daily Trend Bar Chart
    const dailyTrendCtx = document.getElementById('dailyTrendChart');
    if (dailyTrendCtx) {
        const dailyTrendChart = new Chart(dailyTrendCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($dailyTrend, 'date')),
                datasets: [{
                    label: 'Requests',
                    data: @json(array_column($dailyTrend, 'count')),
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
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
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' requests';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json(array_column($monthlyRevenue, 'month')),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: @json(array_column($monthlyRevenue, 'revenue')),
                    borderColor: 'rgba(251, 191, 36, 1)',
                    backgroundColor: 'rgba(251, 191, 36, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgba(251, 191, 36, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
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
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
