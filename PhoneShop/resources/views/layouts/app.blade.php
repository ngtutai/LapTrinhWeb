{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','PhoneShop')</title>

  {{-- KHÔNG DÙNG VITE --}}
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

  {{-- Bootstrap + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  {{-- ===== Styles: Navbar + Theme Slate + Search inline ===== --}}
  <style>
    :root{ --nav-h: 72px; }

    /* Nếu TRANG KHÔNG có hero overlap, tránh navbar che nội dung */
    body { padding-top: var(--nav-h); }

    /* Navbar overlay trên hero video */
    .navbar.nav-overlay{
      background: linear-gradient(to bottom, rgba(0,0,0,.55), rgba(0,0,0,0));
      z-index: 1050;
    }
    .navbar.nav-overlay .navbar-brand,
    .navbar.nav-overlay .nav-link,
    .navbar.nav-overlay .dropdown-item,
    .navbar.nav-overlay .bi{ color:#fff !important; }
    .navbar.nav-overlay .nav-link:hover{ color:#f1f1f1 !important; }
    .navbar.nav-overlay .navbar-toggler{ border-color: rgba(255,255,255,.6); }
    .navbar.nav-overlay .navbar-toggler-icon{ filter: invert(1) grayscale(1); }

    /* Khi cuộn: nền đặc để tương phản tốt */
    .navbar.scrolled{
      background:#0b1320 !important;
      box-shadow:0 .25rem 1rem rgba(0,0,0,.15);
    }

    /* ======= Theme Slate (dark nhẹ) ======= */
    body.theme-slate{
      background:#0b1320;
      color:rgba(255,255,255,.88);
    }
    .theme-slate a{ color:#86b7ff; }
    .theme-slate a:hover{ color:#b6d0ff; }
    .theme-slate h1,.theme-slate h2,.theme-slate h3,
    .theme-slate h4,.theme-slate h5,.theme-slate h6{ color:#fff; }
    .theme-slate .text-muted{ color:rgba(255,255,255,.6)!important; }

    /* Card & input sáng để nội dung dễ đọc */
    .theme-slate .card{ background:#fff; color:#1f2937; border-color:#e9ecef; }
    .theme-slate .card .text-muted{ color:#6c757d!important; }
    .theme-slate .form-control,
    .theme-slate .input-group-text,
    .theme-slate .form-select{ background:#fff; color:#212529; border-color:#dee2e6; }

    /* Pagination hợp tông */
    .theme-slate .pagination .page-link{ background:#0f172a; color:#cfe2ff; border-color:#24364d; }
    .theme-slate .pagination .page-link:hover{ background:#16243a; color:#fff; border-color:#2b4160; }
    .theme-slate .pagination .page-item.active .page-link{ background:#0d6efd; border-color:#0d6efd; color:#fff; }
    .theme-slate .pagination .page-item.disabled .page-link{ background:#0e1524; color:#70809a; border-color:#24364d; }

    /* Offcanvas tối (đồng bộ) */
    .theme-slate .offcanvas{ background:#0f172a; color:#e6edf5; }
    .theme-slate .offcanvas .dropdown-item{ color:#cfe2ff; }
    .theme-slate .offcanvas .dropdown-item:hover{ background:#16243a; color:#fff; }

    /* ===== Search inline trong navbar ===== */
    .nav-searchwrap{ position:relative; }
    .nav-searchbox{
      width:0;
      opacity:0;
      pointer-events:none;
      overflow:hidden;
      transition: width .25s ease, opacity .2s ease;
      background:transparent;
    }
    .nav-searchbox.show{
      width:340px;              /* độ rộng khi mở */
      opacity:1;
      pointer-events:auto;
    }
    .nav-searchbox .input-group-text,
    .nav-searchbox .form-control{ border-radius:.75rem; }
    .nav-searchbox .input-group-text{ background:#fff; border:0; }
    .nav-searchbox .form-control{ background:#fff; border:0; }
    @media (max-width:576px){
      .nav-searchbox.show{ width:220px; }
    }
  </style>

  @stack('styles')
</head>
<body class="theme-slate">

  {{-- NAVBAR ICON (hamburger - logo - search - user - bag) --}}
  <nav class="navbar navbar-expand-lg navbar-dark nav-overlay fixed-top w-100">
    <div class="container-fluid align-items-center px-3">
      {{-- Left: Hamburger --}}
      <button class="btn btn-icon me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMain"
              aria-controls="offcanvasMain" aria-label="Menu">
        <i class="bi bi-list"></i>
      </button>

      {{-- Center: Logo (nên dùng logo trắng) --}}
      <a class="navbar-brand m-0 p-0 mx-auto" href="{{ route('home') }}" aria-label="Home">
        <img src="{{ asset('images/logo-mark-white.svg') }}" alt="PhoneShop" height="28">
      </a>

      {{-- Right: Search inline + User + Bag --}}
      <div class="d-flex ms-auto align-items-center gap-2">
        {{-- Search inline kế bên nút --}}
        <button id="btnSearch" class="btn btn-icon" type="button" aria-label="Tìm kiếm">
          <i class="bi bi-search"></i>
        </button>

        <div class="nav-searchwrap ms-2">
          <form method="get" action="{{ route('home') }}" class="nav-searchbox" id="navSearchBox">
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input id="navSearchInput" type="text" name="q" class="form-control"
                     placeholder="Tìm điện thoại..." value="{{ request('q') }}">
            </div>
          </form>
        </div>

        {{-- User --}}
        @auth
          <div class="dropdown">
            <button class="btn btn-icon" data-bs-toggle="dropdown" aria-label="Tài khoản">
              <i class="bi bi-person"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
              @if (auth()->user()->role === 'admin')
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Quản trị</a></li>
                <li><hr class="dropdown-divider"></li>
              @endif
              <li><a class="dropdown-item" href="{{ route('orders.mine') }}"><i class="bi bi-receipt me-2"></i>Đơn hàng của tôi</a></li>
              <li><a class="dropdown-item" href="{{ route('checkout.create') }}"><i class="bi bi-credit-card me-2"></i>Thanh toán</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn btn-icon" aria-label="Đăng nhập">
            <i class="bi bi-person"></i>
          </a>
        @endauth

        {{-- Cart --}}
        @php $cartCount = session('cart_count', 0); @endphp
        <a href="{{ route('cart.index') }}" class="btn btn-icon position-relative" aria-label="Giỏ hàng">
          <i class="bi bi-bag"></i>
          @if(($cartCount ?? 0) > 0)
            <span class="badge rounded-pill bg-teal position-absolute top-0 start-100 translate-middle p-1 px-2">{{ $cartCount }}</span>
          @endif
        </a>
      </div>
    </div>
  </nav>

  {{-- OFFCANVAS TRÁI --}}
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMain" aria-labelledby="offcanvasMainLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasMainLabel">Danh mục</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="list-unstyled">
        <li><a class="dropdown-item py-2" href="{{ route('home') }}">Trang chủ</a></li>
        <li><a class="dropdown-item py-2" href="{{ route('home') }}">Sản phẩm</a></li>
        <li><a class="dropdown-item py-2" href="{{ route('cart.index') }}">Giỏ hàng</a></li>
        @auth
          <li><a class="dropdown-item py-2" href="{{ route('orders.mine') }}">Đơn hàng của tôi</a></li>
          <li><a class="dropdown-item py-2" href="{{ route('checkout.create') }}">Thanh toán</a></li>
          @if (auth()->user()->role === 'admin')
            <li><hr></li>
            <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">Trang quản trị</a></li>
          @endif
        @endauth
      </ul>
    </div>
  </div>



  {{-- FLASH + CONTENT --}}
  <main class="container">
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-x-circle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <strong>Có lỗi!</strong>
        <ul class="mb-0 mt-2 ps-3">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
  </main>

  {{-- FOOTER (dark) --}}
  @include('partials.footer', [
    'dark' => true,
    'brandSvg' => asset('images/logo-mark-white.svg')
  ])

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Navbar: đổi nền khi cuộn --}}
  <script>
    document.addEventListener('scroll', function () {
      const nav = document.querySelector('.navbar');
      if(!nav) return;
      nav.classList.toggle('scrolled', window.scrollY > 20);
    });
  </script>

  {{-- Search inline behavior --}}
  <script>
    (function () {
      const btn   = document.getElementById('btnSearch');
      const box   = document.getElementById('navSearchBox');
      const input = document.getElementById('navSearchInput');

      if (!btn || !box) return;

      // Toggle mở/đóng khi bấm nút
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        box.classList.toggle('show');
        if (box.classList.contains('show')) setTimeout(() => input && input.focus(), 60);
      });

      // Click ra ngoài thì đóng
      document.addEventListener('click', function (e) {
        if (box.classList.contains('show') && !box.contains(e.target) && e.target !== btn) {
          box.classList.remove('show');
        }
      });

      // Esc để đóng
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') box.classList.remove('show');
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>
