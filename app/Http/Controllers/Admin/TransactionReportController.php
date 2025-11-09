<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionReportController extends Controller
{
    /**
     * Display transaction report page.
     */
    public function index(Request $request)
    {
        $query = FoodRequest::with(['customer', 'foodItem.supplier', 'assignedSupplier', 'foodCategory'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }

        // Filter by supplier (for regular orders or custom requests)
        if ($request->filled('supplier')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('foodItem', function($itemQuery) use ($request) {
                    $itemQuery->where('supplier_id', $request->supplier);
                })->orWhere('assigned_supplier_id', $request->supplier);
            });
        }

        // Filter by order number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Get filtered statistics
        $filteredQuery = clone $query;
        $filteredCount = $filteredQuery->count();
        $filteredRevenue = $filteredQuery->where('status', 'paid')
            ->get()
            ->sum(function($request) {
                return $request->price * $request->quantity;
            });

        $filteredStats = [
            'count' => $filteredCount,
            'revenue' => $filteredRevenue,
        ];

        $transactions = $query->paginate(20);

        // Get customers and suppliers for filters
        $customers = User::whereHas('roles', function($q) {
            $q->where('name', 'customer');
        })->orderBy('name')->get();

        $suppliers = User::whereHas('roles', function($q) {
            $q->where('name', 'supplier');
        })->orderBy('name')->get();

        return view('admin.transactions.index', compact(
            'transactions',
            'customers',
            'suppliers',
            'filteredStats'
        ));
    }

    /**
     * Display the specified transaction.
     */
    public function show(FoodRequest $transaction)
    {
        $transaction->load(['customer', 'foodItem.supplier', 'assignedSupplier', 'foodCategory', 'shippedBy']);

        return view('admin.transactions.show', compact('transaction'));
    }
}

