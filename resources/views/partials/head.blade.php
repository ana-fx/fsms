<!-- Modern Head Section -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'FSMS - FoodSupply Management System')</title>
<meta name="description" content="@yield('description', 'Sistem manajemen pasokan bahan makanan yang menghubungkan customer, admin, dan supplier untuk distribusi makanan yang efisien dan transparan.')">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Custom Styles -->
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
    }

    .card-hover {
        transition: transform 0.2s ease-in-out;
    }

    .card-hover:hover {
        transform: translateY(-2px);
    }
</style>

<!-- CSRF Token Setup -->
<script>
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}'
    };

    // Setup CSRF token for AJAX requests
    document.addEventListener('DOMContentLoaded', function() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            // Only setup axios if it's available
            if (typeof window.axios !== 'undefined' && window.axios.defaults) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            }
        }

        // Refresh CSRF token every 30 minutes
        setInterval(function() {
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    window.Laravel.csrfToken = data.csrf_token;
                })
                .catch(error => console.log('CSRF token refresh failed:', error));
        }, 30 * 60 * 1000); // 30 minutes
    });
</script>

