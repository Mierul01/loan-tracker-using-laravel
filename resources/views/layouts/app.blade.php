<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Dashboard' }} · LoanTrack</title>
    <link rel="icon" type="image/png" href="{{ asset('images/loan.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <div class="app">
        @include('partials.sidebar')

        <div class="sidebar-backdrop" id="sidebar-backdrop" hidden></div>

        <div class="main">
            <header class="topbar">
                <button type="button" class="menu-btn" id="menu-toggle" aria-label="Open menu">☰</button>
                <div class="topbar-left">
                    <span class="date-line">{{ now()->format('D, j M Y') }}</span>
                    <h1>{{ $pageTitle ?? 'Dashboard' }}</h1>
                </div>
                <div class="topbar-actions">
                    @yield('actions')
                </div>
            </header>

            @include('partials.flash')

            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('sidebar');
            var backdrop = document.getElementById('sidebar-backdrop');
            var toggle = document.getElementById('menu-toggle');
            if (!sidebar || !toggle) return;

            function setOpen(open) {
                sidebar.classList.toggle('is-open', open);
                backdrop.hidden = !open;
                document.body.classList.toggle('menu-open', open);
            }

            toggle.addEventListener('click', function () { setOpen(true); });
            backdrop.addEventListener('click', function () { setOpen(false); });
        })();
    </script>
    @stack('scripts')
</body>
</html>
