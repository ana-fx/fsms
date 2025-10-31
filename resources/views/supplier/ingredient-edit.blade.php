@extends('layouts.app')

@section('title', 'Edit Ingredient - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Edit Ingredient</h1>
                    <p class="text-gray-600 mt-2">Update ingredient details</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <form action="{{ route('supplier.ingredients.update', $ingredient) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" name="name" value="{{ old('name', $ingredient->name) }}" required class="w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="food_category_id" required class="w-full px-4 py-2 border {{ $errors->has('food_category_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('food_category_id', $ingredient->food_category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('food_category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $ingredient->price) }}" required class="w-full px-4 py-2 border {{ $errors->has('price') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" name="unit" value="{{ old('unit', $ingredient->unit) }}" required class="w-full px-4 py-2 border {{ $errors->has('unit') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('unit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Purchase <span class="text-red-500">*</span></label>
                                <input type="number" name="min_purchase" value="{{ old('min_purchase', $ingredient->min_purchase) }}" required class="w-full px-4 py-2 border {{ $errors->has('min_purchase') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Minimum quantity per order">
                                @error('min_purchase')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-2 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description', $ingredient->description) }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image (optional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border {{ $errors->has('image') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @if($ingredient->image)
                                <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/'.$ingredient->image) }}" class="text-green-600 hover:text-green-800" target="_blank">View</a></p>
                            @endif
                            @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 text-green-600 border-gray-300 rounded" {{ old('is_active', $ingredient->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <div class="space-x-3">
                                <a href="{{ route('supplier.ingredients') }}" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

