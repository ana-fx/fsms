@extends('layouts.app')

@section('title', 'Purchase Report - FSMS')

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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
                <!-- Header -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Purchase Report</h1>
                    <p class="text-gray-600 mt-2">Tinjau daftar permintaan dan pantau pengeluaran bahan makanan Anda.</p>
                </div>

                <!-- Summary metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg shadow-md p-5 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Order</p>
                                <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($summary['total_orders']) }}</p>
                            </div>
                            <span class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-receipt text-lg"></i>
                            </span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-5 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Pengeluaran</p>
                                <p class="mt-2 text-2xl font-bold text-gray-900">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</p>
                            </div>
                            <span class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-wallet text-lg"></i>
                            </span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-5 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Telah Dibayar</p>
                                <p class="mt-2 text-2xl font-bold text-gray-900">Rp {{ number_format($summary['paid_amount'], 0, ',', '.') }}</p>
                            </div>
                            <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-check-circle text-lg"></i>
                            </span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-5 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Rata-Rata Order</p>
                                <p class="mt-2 text-2xl font-bold text-gray-900">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</p>
                            </div>
                            <span class="flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 text-orange-600">
                                <i class="fas fa-chart-line text-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Filter panel -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Cari</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $filters['search'] }}"
                                    placeholder="Nomor order, nama bahan, kategori"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                >
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                            <select
                                name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                                @php
                                    $statusOptions = [
                                        'all' => 'Semua Status',
                                        'pending' => 'Pending',
                                        'payment_pending' => 'Menunggu Pembayaran',
                                        'paid' => 'Sudah Dibayar',
                                        'shipping' => 'Dalam Pengiriman',
                                        'delivered' => 'Terkirim',
                                        'completed' => 'Selesai',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['status'] ?? 'all') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Dari Tanggal</label>
                            <input
                                type="date"
                                name="date_from"
                                value="{{ $filters['date_from'] }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                            <input
                                type="date"
                                name="date_to"
                                value="{{ $filters['date_to'] }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                        </div>

                        <div class="md:col-span-5 flex items-center justify-end gap-3">
                            <a
                                href="{{ route('customer.reports.purchases') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                <i class="fas fa-undo mr-2"></i> Reset
                            </a>
                            <button
                                type="submit"
                                class="inline-flex items-center px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors"
                            >
                                <i class="fas fa-filter mr-2"></i> Terapkan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Status overview -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach($summary['status_breakdown'] as $status => $count)
                            @php
                                $statusMeta = [
                                    'pending' => ['label' => 'Pending', 'color' => 'bg-gray-100 text-gray-700'],
                                    'payment_pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-yellow-100 text-yellow-800'],
                                    'paid' => ['label' => 'Sudah Dibayar', 'color' => 'bg-green-100 text-green-800'],
                                    'shipping' => ['label' => 'Dalam Pengiriman', 'color' => 'bg-blue-100 text-blue-800'],
                                    'delivered' => ['label' => 'Terkirim', 'color' => 'bg-indigo-100 text-indigo-800'],
                                    'completed' => ['label' => 'Selesai', 'color' => 'bg-purple-100 text-purple-800'],
                                    'rejected' => ['label' => 'Ditolak', 'color' => 'bg-red-100 text-red-800'],
                                ];
                                $percentage = $summary['total_orders'] > 0 ? round(($count / $summary['total_orders']) * 100) : 0;
                            @endphp
                            <div class="flex items-center justify-between {{ $statusMeta[$status]['color'] }} px-4 py-3 rounded-lg border border-transparent">
                                <div>
                                    <p class="text-sm font-semibold">{{ $statusMeta[$status]['label'] }}</p>
                                    <p class="text-xs text-gray-600">{{ $count }} order • {{ $percentage }}%</p>
                                </div>
                                <span class="text-sm font-bold">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Monthly totals -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Total Pengeluaran per Bulan</h3>
                    @if($summary['monthly_totals']->isEmpty())
                        <p class="text-sm text-gray-500">Belum ada data transaksi pada rentang yang dipilih.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($summary['monthly_totals'] as $month)
                                <div class="border border-gray-100 rounded-xl p-4 hover:border-green-200 transition-colors bg-white shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $month['label'] }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $month['orders'] }} order</p>
                                        </div>
                                        <p class="text-base font-bold text-green-600">Rp {{ number_format($month['amount'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Purchase cards -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    @if($requests->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($requests as $request)
                                @php
                                    $unitPrice = $request->price ?? optional($request->foodItem)->price;
                                    $totalAmount = $unitPrice ? (float) $unitPrice * (float) $request->quantity : null;
                                    $badgeStyles = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'payment_pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'shipping' => 'bg-blue-100 text-blue-800',
                                        'delivered' => 'bg-indigo-100 text-indigo-800',
                                        'completed' => 'bg-purple-100 text-purple-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <div class="rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition-shadow p-5 flex flex-col h-full">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $request->order_number }}</p>
                                            <p class="text-xs text-gray-500">{{ optional($request->created_at)->format('d M Y') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeStyles[$request->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            <i class="fas fa-circle text-[8px] mr-2"></i>
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>

                                    <div class="mt-4 space-y-2 text-sm text-gray-700">
                                        <p class="font-medium text-gray-900">{{ $request->foodItem->name ?? $request->title }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $request->foodCategory->name ?? 'Tanpa kategori' }}
                                            @if($request->assignedSupplier)
                                                • Supplier: {{ $request->assignedSupplier->name }}
                                            @elseif($request->foodItem && $request->foodItem->supplier)
                                                • Supplier: {{ $request->foodItem->supplier->name }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Kuantitas</p>
                                            <p class="mt-1 font-semibold text-gray-900">
                                                {{ number_format($request->quantity, 2, ',', '.') }} {{ $request->unit }}
                                            </p>
                                            @if($unitPrice)
                                                <p class="text-xs text-gray-500">Rp {{ number_format($unitPrice, 0, ',', '.') }} / {{ $request->unit }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                                            <p class="mt-1 font-semibold text-gray-900">
                                                @if($totalAmount !== null)
                                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                                @else
                                                    <span class="text-gray-400">Menunggu penawaran</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-dashed border-gray-200 flex items-center justify-between">
                                        <a href="{{ route('customer.requests.show', $request) }}"
                                           class="inline-flex items-center px-3 py-2 text-sm text-green-600 border border-green-200 rounded-lg hover:bg-green-50 transition-colors">
                                            <i class="fas fa-file-invoice mr-2"></i> Lihat tagihan
                                        </a>
                                        <a href="{{ route('customer.requests.index', ['search' => $request->order_number]) }}"
                                           class="text-xs text-gray-500 hover:text-gray-700">
                                            Riwayat →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($requests->hasPages())
                            <div class="mt-6">
                                {{ $requests->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-16">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Belum ada data pembelian</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                @if($filters['search'] || ($filters['status'] ?? 'all') !== 'all' || $filters['date_from'] || $filters['date_to'])
                                    Coba sesuaikan filter atau bersihkan untuk melihat seluruh pesanan Anda.
                                @else
                                    Setelah melakukan pemesanan, ringkasan keuangan akan tampil di sini.
                                @endif
                            </p>
                            <a href="{{ route('customer.ingredients') }}"
                               class="mt-6 inline-flex items-center px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors">
                                <i class="fas fa-shopping-basket mr-2"></i> Telusuri Bahan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
