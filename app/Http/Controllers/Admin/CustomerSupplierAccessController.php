<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerSupplierAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerSupplierAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->with(['accessibleSuppliers' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email');
            }])
            ->orderBy('name')
            ->get();

        $suppliers = User::whereHas('roles', function ($query) {
            $query->where('name', 'supplier');
        })
            ->orderBy('name')
            ->get();

        $assignmentsQuery = CustomerSupplierAccess::with(['customer', 'supplier', 'creator'])
            ->orderByDesc('created_at');

        if ($request->filled('customer_id')) {
            $assignmentsQuery->where('customer_id', $request->input('customer_id'));
        }

        if ($request->filled('supplier_id')) {
            $assignmentsQuery->where('supplier_id', $request->input('supplier_id'));
        }

        $assignments = $assignmentsQuery->paginate(15)->appends($request->query());

        return view('admin.customer-supplier-access.index', [
            'customers' => $customers,
            'suppliers' => $suppliers,
            'assignments' => $assignments,
            'filters' => $request->only(['customer_id', 'supplier_id']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->orderBy('name')
            ->get();

        $suppliers = User::whereHas('roles', function ($query) {
            $query->where('name', 'supplier');
        })
            ->orderBy('name')
            ->get();

        $selectedCustomerId = $request->input('customer_id');
        $selectedSupplierIds = collect($request->input('supplier_ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($selectedCustomerId) {
            $customer = $customers->firstWhere('id', (int) $selectedCustomerId);
            if ($customer && $selectedSupplierIds->isEmpty()) {
                $selectedSupplierIds = $customer->accessibleSuppliers->pluck('id');
            }
        }

        return view('admin.customer-supplier-access.create', [
            'customers' => $customers,
            'suppliers' => $suppliers,
            'selectedCustomerId' => $selectedCustomerId,
            'selectedSupplierIds' => $selectedSupplierIds,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'supplier_ids' => ['nullable', 'array'],
            'supplier_ids.*' => ['distinct', 'exists:users,id'],
        ]);

        $customer = User::where('id', $validated['customer_id'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            })
            ->firstOrFail();

        $supplierIdsInput = collect($request->input('supplier_ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $supplierIds = User::whereIn('id', $supplierIdsInput)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'supplier');
            })
            ->pluck('id')
            ->all();

        if (!empty($supplierIdsInput) && count($supplierIds) !== count($supplierIdsInput)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['supplier_ids' => 'Selected suppliers are not valid suppliers.']);
        }

        $syncPayload = collect($supplierIds)->mapWithKeys(function ($supplierId) {
            return [$supplierId => ['created_by' => Auth::id()]];
        })->all();

        $customer->accessibleSuppliers()->sync($syncPayload);

        return redirect()
            ->route('admin.customer-access.index')
            ->with('success', 'Supplier access updated for ' . $customer->name . '.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerSupplierAccess $customerAccess)
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->orderBy('name')
            ->get();

        $suppliers = User::whereHas('roles', function ($query) {
            $query->where('name', 'supplier');
        })
            ->orderBy('name')
            ->get();

        return view('admin.customer-supplier-access.edit', [
            'access' => $customerAccess,
            'customers' => $customers,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerSupplierAccess $customerAccess)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:users,id'],
            'supplier_id' => ['required', 'exists:users,id'],
        ]);

        $customer = User::where('id', $validated['customer_id'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            })
            ->firstOrFail();

        $supplier = User::where('id', $validated['supplier_id'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'supplier');
            })
            ->firstOrFail();

        $duplicateExists = CustomerSupplierAccess::where('customer_id', $customer->id)
            ->where('supplier_id', $supplier->id)
            ->where('id', '!=', $customerAccess->id)
            ->exists();

        if ($duplicateExists) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['supplier_id' => 'This supplier is already assigned to the selected customer.']);
        }

        $customerAccess->update([
            'customer_id' => $customer->id,
            'supplier_id' => $supplier->id,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.customer-access.index')
            ->with('success', 'Access record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerSupplierAccess $customerAccess)
    {
        $customerAccess->delete();

        return redirect()
            ->route('admin.customer-access.index')
            ->with('success', 'Access record removed successfully.');
    }
}

