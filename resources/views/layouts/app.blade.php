<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.header')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @yield('scripts')
</body>
</html>
