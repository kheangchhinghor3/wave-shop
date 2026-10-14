<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WAVE SHOP')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/wave.css') }}">
</head>
<body>

    <header class="site-header">
        <div class="container nav-inner">
            <a href="{{ url('/') }}" class="logo">WAVE <span>SHOP</span></a>

            <ul class="nav-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/products') }}">Products</a></li>
                <li><a href="#">Cart</a></li>
                <li><a href="#" class="btn-nav">Login</a></li>
            </ul>
        </div>
    </header>

    <main class="site-main">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-inner">
                <div>
                    <h4>WAVE SHOP</h4>
                    <p>K-pop inspired goods, riding the wave.</p>
                </div>
                <div>
                    <h4>Shop</h4>
                    <a href="{{ url('/products') }}">All Products</a>
                </div>
                <div>
                    <h4>Help</h4>
                    <a href="#">Contact</a>
                    <a href="#">Shipping</a>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} WAVE SHOP. A university project.
            </div>
        </div>
    </footer>

</body>
</html>