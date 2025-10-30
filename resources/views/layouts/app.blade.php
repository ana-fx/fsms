<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
</head>
<body class="bg-gray-50 overflow-x-hidden">
    <div class="flex flex-col w-full">
        @include('partials.header')
        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            @yield('content')
        </main>

        <div class="mt-auto">
            @include('partials.footer')
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
