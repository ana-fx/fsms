<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
</head>
<body class="bg-gray-50">
    <div class="flex flex-col">
        @include('partials.header')
        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
