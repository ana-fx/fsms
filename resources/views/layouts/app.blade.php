<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.header')
</head>
<body class="bg-gray-50">
    <div class="flex flex-col min-h-screen">
        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @yield('scripts')
</body>
</html>
