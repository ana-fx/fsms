@extends('layouts.app')

@section('title', 'Kelola Program - FSMS')

@section('content')
<div class="flex min-h-screen">
    @include('customer.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64 min-h-screen">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Program</h1>
            <p class="text-gray-600 mt-2">Manajemen program dan kegiatan</p>
        </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">Program Management</h2>

                <div class="mb-6">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Create New Program</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Sample Program Cards -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-purple-100 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-purple-600 font-semibold">Education Program</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Scholarship Program</h3>
                        <p class="text-gray-600 mb-2">Providing educational opportunities for underprivileged students...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$15,000</span>
                            <span class="text-sm text-gray-500">Raised</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-green-100 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-green-600 font-semibold">Health Program</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Medical Assistance</h3>
                        <p class="text-gray-600 mb-2">Supporting healthcare access for communities in need...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$8,500</span>
                            <span class="text-sm text-gray-500">Raised</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-blue-100 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">Community Program</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Food Distribution</h3>
                        <p class="text-gray-600 mb-2">Distributing food supplies to families in need...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$12,000</span>
                            <span class="text-sm text-gray-500">Raised</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Program Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">8</div>
                            <div class="text-sm text-gray-600">Active Programs</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">$35,500</div>
                            <div class="text-sm text-gray-600">Total Raised</div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">150</div>
                            <div class="text-sm text-gray-600">Beneficiaries</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">25</div>
                            <div class="text-sm text-gray-600">Donors</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </div>
</div>
@endsection
