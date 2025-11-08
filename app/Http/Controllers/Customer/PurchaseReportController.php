<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PurchaseReportController extends Controller
{
    /**
     * Display the purchase report for the authenticated customer.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'status' => 'nullable|in:all,pending,payment_pending,paid,shipping,delivered,completed,rejected',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'search' => 'nullable|string|max:255',
        ]);

        $baseQuery = FoodRequest::with(['foodCategory', 'foodItem.supplier', 'assignedSupplier'])
            ->byCustomer($user->id);

        // Apply filters
        if (!empty($validated['status']) && $validated['status'] !== 'all') {
            $baseQuery->where('status', $validated['status']);
        }

        if (!empty($validated['date_from'])) {
            $baseQuery->whereDate('created_at', '>=', $validated['date_from']);
        }

        if (!empty($validated['date_to'])) {
            $baseQuery->whereDate('created_at', '<=', $validated['date_to']);
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $baseQuery->where(function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('foodItem', function ($itemQuery) use ($search) {
                        $itemQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('foodCategory', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $filteredRequests = (clone $baseQuery)->get();

        $summary = $this->buildSummary($filteredRequests);

        $requests = $baseQuery
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('customer.reports.purchases', [
            'requests' => $requests,
            'summary' => $summary,
            'filters' => [
                'status' => $validated['status'] ?? 'all',
                'date_from' => $validated['date_from'] ?? null,
                'date_to' => $validated['date_to'] ?? null,
                'search' => $validated['search'] ?? null,
            ],
        ]);
    }

    /**
     * Build summary metrics for the report.
     */
    private function buildSummary(Collection $requests): array
    {
        $totalOrders = $requests->count();

        $monetaryTotals = $requests->reduce(function ($carry, FoodRequest $request) {
            $unitPrice = $request->price ?? optional($request->foodItem)->price;
            $amount = $unitPrice ? (float) $unitPrice * (float) $request->quantity : 0.0;

            $carry['total_amount'] += $amount;

            if (in_array($request->status, ['paid', 'shipping', 'delivered', 'completed'])) {
                $carry['paid_amount'] += $amount;
            }

            return $carry;
        }, [
            'total_amount' => 0.0,
            'paid_amount' => 0.0,
        ]);

        $statusBreakdown = [
            'pending' => 0,
            'payment_pending' => 0,
            'paid' => 0,
            'shipping' => 0,
            'delivered' => 0,
            'completed' => 0,
            'rejected' => 0,
        ];

        foreach ($requests as $request) {
            if (array_key_exists($request->status, $statusBreakdown)) {
                $statusBreakdown[$request->status]++;
            }
        }

        $averageOrderValue = $totalOrders > 0 ? $monetaryTotals['total_amount'] / $totalOrders : 0.0;

        $monthlyTotals = $requests
            ->filter(fn (FoodRequest $request) => $request->created_at !== null)
            ->groupBy(fn (FoodRequest $request) => $request->created_at->format('Y-m'))
            ->sortKeysDesc()
            ->map(function ($group, $month) {
                $totalAmount = $group->reduce(function ($carry, FoodRequest $request) {
                    $unitPrice = $request->price ?? optional($request->foodItem)->price;
                    $amount = $unitPrice ? (float) $unitPrice * (float) $request->quantity : 0.0;
                    return $carry + $amount;
                }, 0.0);

                $label = Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');

                return [
                    'label' => $label,
                    'orders' => $group->count(),
                    'amount' => $totalAmount,
                ];
            })
            ->values();

        return [
            'total_orders' => $totalOrders,
            'total_amount' => $monetaryTotals['total_amount'],
            'paid_amount' => $monetaryTotals['paid_amount'],
            'average_order_value' => $averageOrderValue,
            'status_breakdown' => $statusBreakdown,
            'monthly_totals' => $monthlyTotals,
        ];
    }
}
