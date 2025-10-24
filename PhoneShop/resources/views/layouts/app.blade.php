<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'PhoneShop')</title>

    {{-- Build assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap + Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" />

    @stack('styles')
</head>

<body class="bg-light">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">

            {{-- Brand --}}
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
                <i class="fa-solid fa-mobile-screen-button me-2"></i>
                PhoneShop
            </a>

            {{-- Toggler (mobile) --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
                aria-controls="navMain" aria-expanded="false" aria-label="Mở điều hướng">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Collapsible content --}}
            <div class="collapse navbar-collapse" id="navMain">

                {{-- Search (đặt trái trên desktop, full-width trên mobile) --}}
                <form class="d-flex ms-lg-auto my-3 my-lg-0 w-75 w-lg-auto" method="get" action="{{ route('home') }}">
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white border-secondary d-none d-sm-inline-flex">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input class="form-control border-secondary" name="q" value="{{ request('q') }}"
                            placeholder="Tìm điện thoại...">
                        <button class="btn btn-primary">
                            Tìm
                        </button>
                    </div>
                </form>

                {{-- Menu phải --}}
                <ul class="navbar-nav ms-lg-3 align-items-lg-center">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.mine') }}">
                                <i class="fa-solid fa-receipt me-1"></i>Đơn
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fa-solid fa-cart-shopping me-1"></i>Giỏ
                            </a>
                        </li>

                        {{-- User dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user me-1"></i>
                                <span class="text-truncate" style="max-width: 160px;">
                                    {{ auth()->user()->name }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (auth()->user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-gauge me-2 text-primary"></i>Trang quản trị
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="px-2 py-1">
                                        @csrf
                                        <button class="btn btn-danger w-100">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fa-solid fa-cart-shopping me-1"></i>Giỏ
                            </a>
                        </li>
                        <li class="nav-item mt-2 mt-lg-0">
                            <a class="btn btn-primary" href="{{ route('login') }}">
                                <i class="fa-solid fa-right-to-bracket me-1"></i>Đăng nhập
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- MAIN --}}
    <main class="container my-4">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-regular fa-circle-check me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-regular fa-circle-xmark me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong>Có lỗi!</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-top py-4">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <div class="small text-muted">© {{ now()->year }} PhoneShop. All rights reserved.</div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-success btn-sm d-md-none">
                    <i class="fa-solid fa-cart-shopping me-1"></i>Giỏ
                </a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm d-md-none">
                        <i class="fa-solid fa-right-to-bracket me-1"></i>Đăng nhập
                    </a>
                @endguest
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @push('styles')
        <style>
            /* Nav link hover & active subtle */
            .navbar .nav-link {
                border-radius: .5rem;
            }

            .navbar .nav-link:hover {
                background-color: rgba(255, 255, 255, .1);
            }

            .dropdown-menu {
                border-radius: .75rem;
                overflow: hidden;
            }

            /* Input group tone dark border */
            .input-group .form-control:focus {
                box-shadow: none;
                border-color: #86b7fe;
            }
        </style>
    @endpush

    @stack('scripts')
</body>

</html>
