<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodRequest;
use App\Models\User;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total statistics
        $totalRequests = FoodRequest::count();
        $totalSuppliers = User::whereHas('roles', function($q) { 
            $q->where('name', 'supplier'); 
        })->count();
        $totalCustomers = User::whereHas('roles', function($q) { 
            $q->where('name', 'customer'); 
        })->count();
        
        // Requests by status (only statuses that are actually used)
        $stats = [
            'pending' => FoodRequest::where('status', 'pending')->count(),
            'payment_pending' => FoodRequest::where('status', 'payment_pending')->count(),
            'paid' => FoodRequest::where('status', 'paid')->count(),
            'rejected' => FoodRequest::where('status', 'rejected')->count(),
        ];
        
        // Status distribution for chart
        $statusDistribution = $stats;
        
        // Calculate total revenue (sum of all paid orders)
        $totalRevenue = FoodRequest::with(['foodItem'])
            ->where('status', 'paid')
            ->get()
            ->sum(function($request) {
                if ($request->foodItem) {
                    return $request->foodItem->price * $request->quantity;
                } elseif ($request->price) {
                    return $request->price * $request->quantity;
                }
                return 0;
            });
        
        // Calculate monthly revenue (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = FoodRequest::with(['foodItem'])
                ->where('status', 'paid')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->get()
                ->sum(function($request) {
                    if ($request->foodItem) {
                        return $request->foodItem->price * $request->quantity;
                    } elseif ($request->price) {
                        return $request->price * $request->quantity;
                    }
                    return 0;
                });
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue,
            ];
        }
        
        // Daily trend data (last 30 days)
        $dailyTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = FoodRequest::whereDate('created_at', $date->format('Y-m-d'))->count();
            $dailyTrend[] = [
                'date' => $date->format('d M'),
                'count' => $count,
            ];
        }
        
        // Recent pending requests (custom requests)
        $pendingCustomRequests = FoodRequest::whereNull('food_item_id')
            ->where('status', 'pending')
            ->with(['customer', 'foodCategory'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Top suppliers by order count
        $topSuppliers = User::whereHas('roles', function($q) { 
            $q->where('name', 'supplier'); 
        })
        ->get()
        ->map(function($supplier) {
            $supplierItems = FoodItem::where('supplier_id', $supplier->id)->pluck('id');
            $supplier->total_orders = FoodRequest::whereIn('food_item_id', $supplierItems)
                ->where('status', 'paid')
                ->count();
            return $supplier;
        })
        ->sortByDesc('total_orders')
        ->take(5)
        ->values();
        
        // Requests by type (regular vs custom)
        $regularRequests = FoodRequest::whereNotNull('food_item_id')->count();
        $customRequests = FoodRequest::whereNull('food_item_id')->count();
        
        // Active ingredients count
        $activeIngredients = FoodItem::where('is_active', true)->count();
        $totalIngredients = FoodItem::count();
        
        return view('admin.dashboard', compact(
            'totalRequests',
            'totalSuppliers',
            'totalCustomers',
            'stats',
            'statusDistribution',
            'totalRevenue',
            'monthlyRevenue',
            'dailyTrend',
            'pendingCustomRequests',
            'topSuppliers',
            'regularRequests',
            'customRequests',
            'activeIngredients',
            'totalIngredients'
        ));
    }
}

