{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','PhoneShop')</title>

  {{-- Bootstrap + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{ --nav-h:76px; }

    /* ===== Base ===== */
    html,body{height:100%}
    body{
      padding-top: var(--nav-h);
      background:#0b1320;
      color:rgba(255,255,255,.88);
      overflow-x:hidden;
    }
    .navbar{
  min-height: var(--nav-h);
  background:#0b1320;          /* Nền cứng mặc định cho ALL trang */
}

    /* ===== Navbar over hero ===== */
    /* Có banner: nav TRONG SUỐT hoàn toàn, NẰM ĐÈ lên banner (không chừa khoảng) */
    body.has-hero .navbar{
      background: transparent !important;
      border-bottom: 0 !important;
      box-shadow: none !important;
      z-index: 1050;
    }
    /* Kéo banner lên sát dưới nav (xóa khoảng hở do padding-top body) */
    body.has-hero .hero-banner{
      margin-top: calc(var(--nav-h) * -1) !important;
    }

    /* Khi lướt xuống (dù có hay không có banner): nav NỀN CỨNG */
    .navbar.scrolled{
      background:#0b1320 !important;
      box-shadow:0 .25rem 1rem rgba(0,0,0,.15);
    }

    /* Màu chữ/icon khi nav đè trên video */
    body.has-hero .navbar .navbar-brand,
    body.has-hero .navbar .nav-link,
    body.has-hero .navbar .bi{ color:#fff !important; }
    body.has-hero .navbar .nav-link:hover{ color:#f1f1f1 !important; }
    body.has-hero .navbar .navbar-toggler{ border-color:rgba(255,255,255,.6); }
    body.has-hero .navbar .navbar-toggler-icon{ filter: invert(1) grayscale(1); }

    /* ===== Search inline ===== */
    .nav-searchwrap{ position:relative; }
    .nav-searchbox{
      width:0; opacity:0; pointer-events:none; overflow:hidden;
      transition: width .25s ease, opacity .2s ease;
    }
    .nav-searchbox.show{
      width:340px; opacity:1; pointer-events:auto;
    }
    .nav-searchbox .input-group-text,
    .nav-searchbox .form-control{ border-radius:.75rem; }
    .nav-searchbox .input-group-text{ background:#fff; border:0; }
    .nav-searchbox .form-control{ background:#fff; border:0; }
    @media (max-width:576px){
      .nav-searchbox.show{ width:220px; }
    }

    /* Box gợi ý search */
    #navSearchSuggest{
      position:absolute;
      top:100%;
      left:0;
      right:0;
      margin-top:.25rem;
      background:#fff;
      color:#212529;
      border-radius:.75rem;
      box-shadow:0 .5rem 1.5rem rgba(0,0,0,.35);
      z-index:2000;
      max-height:340px;
      overflow:auto;
      display:none;
    }
    #navSearchSuggest .list-group-item{
      cursor:pointer;
    }
    #navSearchSuggest .list-group-item:hover{
      background:#0d6efd;
      color:#fff;
    }
    #navSearchSuggest .list-group-item:hover .text-muted{
      color:rgba(255,255,255,.8) !important;
    }

    /* Nút icon gọn + badge teal (nhỏ gọn) */
    .btn-icon{
      --btn-size:40px;
      width:var(--btn-size);
      height:var(--btn-size);
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:0;
      border-radius:.75rem;
      color:inherit;
    }
    .btn-icon .bi{ font-size:1.15rem; }
    .bg-teal{ background-color:#20c997 !important; }

    /* ===== HERO (video) – full-bleed chuyên nghiệp, KHÔNG lệch ===== */
    .hero-video-wrapper{ position:relative; overflow:hidden; }
    .hero-video-wrapper.full-bleed{
      width:100vw; max-width:100vw;
      margin-left: calc(50% - 50vw);
      margin-right: calc(50% - 50vw);
    }
    .hero-video{
      display:block;
      width:100%;
      height: clamp(340px, 45vw, 720px);
      object-fit: cover;
    }
    /* 1 khuôn desktop: cố định chiều cao */
    @media (min-width: 992px){
      .hero-video{ height: 620px; }
    }
    /* Lớp tối nhẹ phía trên giúp nav đọc dễ hơn */
    .hero-video-wrapper::before{
      content:"";
      position:absolute;
      inset:0 0 auto 0;
      height:80px;
      background: linear-gradient(to bottom, rgba(0,0,0,.55), rgba(0,0,0,0));
      pointer-events:none;
    }

    /* Cards / form trên nền tối */
    .card{
      background:#fff;
      color:#1f2937;
      border-color:#e9ecef;
    }
    .form-control,
    .input-group-text,
    .form-select{
      background:#fff;
      color:#212529;
      border-color:#dee2e6;
    }

    /* Logo giữa */
    .navbar .brand-logo{ height:40px; width:auto; }
    @media (min-width:992px){
      .navbar .brand-logo{ height:48px; }
    }

    .brand-chips{ overflow-x:auto; white-space:nowrap; }
    .brand-chip{
      padding:.5rem 1rem;
      border-radius:50px;
      cursor:pointer;
      border:1px solid rgba(255,255,255,.25);
      color:#fff;
      background:rgba(255,255,255,.1);
      font-weight:600;
      transition:.2s;
    }
    .brand-chip:hover{ background:rgba(255,255,255,.2); }
    .brand-chip.active{ background:#0d6efd; border-color:#0d6efd; }
  </style>

  @stack('styles')
</head>
<body class="theme-slate">

  {{-- NAVBAR --}}
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top w-100">
    <div class="container-fluid align-items-center px-3 position-relative">
      {{-- Left: Hamburger --}}
      <button class="btn btn-icon me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMain" aria-controls="offcanvasMain" aria-label="Menu">
        <i class="bi bi-list"></i>
      </button>

      {{-- Center: Logo (giữa tuyệt đối) --}}
      <a class="navbar-brand m-0 p-0 position-absolute start-50 top-50 translate-middle-x translate-middle-y z-1 d-flex align-items-center"
         href="{{ route('home') }}" aria-label="Home">
        <span class="logo-text">TCT</span>
      </a>

      {{-- Right: Search + User + Cart --}}
      <div class="d-flex ms-auto align-items-center gap-2">
        <button id="btnSearch" class="btn btn-icon" type="button" aria-label="Tìm kiếm">
          <i class="bi bi-search"></i>
        </button>

        <div class="nav-searchwrap ms-2">
          <form method="get" action="{{ route('home') }}" class="nav-searchbox" id="navSearchBox" role="search" aria-label="Tìm kiếm sản phẩm">
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input id="navSearchInput" type="text" name="q" class="form-control" placeholder="Tìm điện thoại..." value="{{ request('q') }}">
            </div>
          </form>

          {{-- Gợi ý tìm kiếm --}}
          <div id="navSearchSuggest"></div>
        </div>

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
                <form method="POST" action="{{ route('logout') }}">@csrf
                  <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn btn-icon" aria-label="Đăng nhập"><i class="bi bi-person"></i></a>
        @endauth

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

  {{-- HERO BANNER (video) – bật qua biến $showBanner ở view con --}}
  @if (!empty($showBanner))
    <section class="hero-banner">
      <div class="hero-video-wrapper full-bleed">
        <video class="hero-video" playsinline muted autoplay loop
               poster="{{ asset('images/hero-poster.jpg') }}">
          <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
          {{-- <source src="{{ asset('videos/hero.webm') }}" type="video/webm"> --}}
          Trình duyệt của bạn không hỗ trợ video HTML5.
        </video>
      </div>
    </section>
  @endif

  {{-- NỘI DUNG CHÍNH --}}
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
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
      </div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
  </main>

  {{-- FOOTER --}}
  @include('partials.footer', ['dark' => true, 'brandSvg' => asset('images/logo-mark-white.svg')])

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Search inline behavior --}}
  <script>
    (function(){
      const btn   = document.getElementById('btnSearch');
      const box   = document.getElementById('navSearchBox');
      const input = document.getElementById('navSearchInput');
      if (!btn || !box) return;

      btn.addEventListener('click', function(e){
        e.stopPropagation();
        box.classList.toggle('show');
        if (box.classList.contains('show')) {
          setTimeout(() => input && input.focus(), 60);
        }
      });

      document.addEventListener('click', function(e){
        if (box.classList.contains('show') && !box.contains(e.target) && e.target !== btn) {
          box.classList.remove('show');
        }
      });

      document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') box.classList.remove('show');
      });
    })();
  </script>

  {{-- Navbar: top trong suốt nếu có banner; lướt xuống là cứng --}}
  <script>
    (function(){
      const nav = document.querySelector('.navbar');
      if (!nav) return;
      const hasHero = !!document.querySelector('.hero-banner');
      document.body.classList.toggle('has-hero', hasHero);

      function onScroll(){
        nav.classList.toggle('scrolled', window.scrollY > 0);
      }
      onScroll();
      document.addEventListener('scroll', onScroll, { passive: true });
    })();
  </script>

  {{-- GỢI Ý TÌM KIẾM cho ô navSearchInput --}}
  <script>
    (function(){
      const input   = document.getElementById('navSearchInput');
      const suggest = document.getElementById('navSearchSuggest');
      if (!input || !suggest) return;

      let timer = null;

      function hideSuggest(){
        suggest.style.display = 'none';
        suggest.innerHTML = '';
      }

      function renderSuggest(data){
        const products   = Array.isArray(data.products)   ? data.products   : [];
        const categories = Array.isArray(data.categories) ? data.categories : [];

        if (products.length === 0 && categories.length === 0){
          hideSuggest();
          return;
        }

        let html = '';

        if (products.length){
          html += '<div class="border-bottom small fw-semibold text-muted px-3 pt-2 pb-1">Sản phẩm</div>';
          products.forEach(function(p){
            html += `
              <a href="${p.url}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 px-3 py-2">
                ${p.thumb ? `<img src="${p.thumb}" alt="" style="width:36px;height:36px;object-fit:cover;border-radius:8px;">` : ''}
                <div class="flex-fill">
                  <div class="fw-semibold small mb-0 text-truncate">${p.name}</div>
                  ${p.price ? `<div class="text-muted small">${p.price}</div>` : ''}
                </div>
              </a>
            `;
          });
        }

        if (categories.length){
          html += '<div class="border-top small fw-semibold text-muted px-3 pt-2 pb-1">Danh mục</div>';
          categories.forEach(function(c){
            html += `
              <a href="${c.url}" class="list-group-item list-group-item-action px-3 py-2">
                <i class="bi bi-folder2-open me-2"></i>
                <span class="small">${c.name}</span>
              </a>
            `;
          });
        }

        suggest.innerHTML = html;
        suggest.style.display = 'block';
      }

      input.addEventListener('input', function(){
        const q = this.value.trim();

        if (timer) clearTimeout(timer);

        if (q.length < 2){
          hideSuggest();
          return;
        }

        timer = setTimeout(function(){
          fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`)
            .then(function(res){ return res.json(); })
            .then(function(data){ renderSuggest(data || {}); })
            .catch(function(err){
              console.error('Search suggest error', err);
              hideSuggest();
            });
        }, 250); // debounce
      });

      // Ẩn khi click ngoài
      document.addEventListener('click', function(e){
        if (!suggest.contains(e.target) && e.target !== input){
          hideSuggest();
        }
      });

      // Enter: submit form, ẩn gợi ý
      input.addEventListener('keydown', function(e){
        if (e.key === 'Escape'){
          hideSuggest();
        }
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>
