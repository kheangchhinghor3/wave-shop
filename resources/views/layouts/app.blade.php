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

                @auth
                    <li class="nav-user">Hi, {{ Auth::user()->name }}</li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-nav btn-logout">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="btn-nav">Login</a></li>
                    <li><a href="{{ route('register') }}" class="btn-nav btn-nav-blue">Register</a></li>
                @endauth
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

    <script>
        // Show / Hide password buttons (works for any .toggle-password button)
        document.addEventListener('click', function (event) {
            const button = event.target.closest('.toggle-password');
            if (!button) return;

            const input = button.parentElement.querySelector('input');
            const isShowing = input.type === 'text';

            input.type = isShowing ? 'password' : 'text';
            button.textContent = isShowing ? 'Show' : 'Hide';
        });
    </script>

</body>
</html>