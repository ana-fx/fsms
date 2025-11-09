<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\FoodCategory;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display the product page with all available products.
     */
    public function index(Request $request)
    {
        $query = FoodItem::with(['foodCategory.parent', 'foodCategory.children', 'supplier'])
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Filter by category if selected
        if ($request->has('category') && $request->category) {
            $category = FoodCategory::find($request->category);
            if ($category) {
                // If parent category, include all items from sub-categories
                if ($category->isParent()) {
                    $categoryIds = $category->children()->pluck('id')->prepend($category->id);
                    $query->whereIn('food_category_id', $categoryIds);
                } else {
                    // If sub-category, only show items from that category
                    $query->where('food_category_id', $request->category);
                }
            }
        }

        // Search by name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(12);

        // Get parent categories with their children
        $categories = FoodCategory::active()
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->active()->ordered();
            }])
            ->ordered()
            ->get();

        // If AJAX request, return JSON with HTML
        if ($request->ajax()) {
            $html = '';

            if ($products->count() > 0) {
                $html .= '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
                foreach ($products as $product) {
            $html .= view('customer.partials.ingredient-card', ['product' => $product])->render();
                }
                $html .= '</div>';

                // Pagination
                $html .= '<div id="paginationContainer" class="mt-6">';
                $html .= $products->appends($request->query())->links()->toHtml();
                $html .= '</div>';
            } else {
                $emptyMessage = ($request->has('search') && $request->search) || ($request->has('category') && $request->category)
                    ? 'Try different keywords or filters'
                    : 'No ingredients available yet';

                $html .= '<div class="text-center py-12">';
                $html .= '<i class="fas fa-box-open text-5xl text-gray-400 mb-4"></i>';
                $html .= '<h3 class="text-xl font-medium text-gray-900 mb-2">No ingredients found</h3>';
                $html .= '<p class="text-gray-500 mb-6">' . $emptyMessage . '</p>';

                if (($request->has('search') && $request->search) || ($request->has('category') && $request->category)) {
                    $html .= '<button onclick="clearSearch()" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">';
                    $html .= '<i class="fas fa-arrow-left mr-2"></i>View All Ingredients';
                    $html .= '</button>';
                }
                $html .= '</div>';
            }

            return response()->json(['html' => $html]);
        }

        return view('customer.ingredient', compact('products', 'categories'));
    }
}

