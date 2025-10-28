{{-- resources/views/layouts/guest.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'PhoneShop') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --font: 'Figtree', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      --bg: #f6f7f9;
      --card-bg: #fff;
      --text: #1f2937;
      --subtle: #6b7280;
      --primary: #0d6efd;
      --radius: 16px;
      --shadow: 0 10px 30px rgba(16,24,40,.06);
      --border: #e5e7eb;
      --focus: #86b7fe;
    }

    html,body{height:100%}
    body{ font-family:var(--font); background:var(--bg); color:var(--text); }

    .auth-card{
      background:var(--card-bg);
      border:1px solid var(--border);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
    }
    .auth-card .card-body{ padding:28px; }
    @media (min-width:576px){ .auth-card .card-body{ padding:36px; } }

    .brand-link{ display:inline-flex; align-items:center; gap:.6rem; text-decoration:none; color:inherit; }
    .brand-link img{ height:44px; filter:drop-shadow(0 1px 2px rgba(0,0,0,.25)); }
    .brand-link .brand-name{ font-weight:600; font-size:1.5rem; letter-spacing:.2px; }

    /* ===== Form skin (không sửa view con) ===== */
    .auth-card form{ width:100%; }
    .auth-card form label{ display:block; font-size:.95rem; margin-bottom:.4rem; color:#111827; font-weight:500; }
    .auth-card form input[type="email"],
    .auth-card form input[type="password"],
    .auth-card form input[type="text"],
    .auth-card form input[type="number"],
    .auth-card form input[type="tel"]{
      width:100%; padding:.72rem .9rem; border:1px solid #d1d5db; border-radius:.75rem;
      background:#fff; color:#111827; outline:none; transition:border-color .15s, box-shadow .15s;
    }
    .auth-card form input:focus{ border-color:var(--focus); box-shadow:0 0 0 .2rem rgba(13,110,253,.15); }
    .auth-card .form-check{ display:flex; align-items:center; gap:.5rem; margin:.5rem 0 1rem; }
    .auth-card .form-check input[type="checkbox"]{ width:1.1rem; height:1.1rem; accent-color:var(--primary); }
    .auth-card .form-check label{ margin:0; color:#374151; font-weight:400; }

    /* ===== HÀNG NÚT HÀNH ĐỘNG (fix spacing + style link đăng ký) ===== */
    /* Breeze thường bọc bằng div.mt-4; ta ép cột dọc + gap đẹp */
    .auth-card .card-body .mt-4{
      display:flex; flex-direction:column; align-items:stretch; gap:.6rem;
      margin-top:1rem !important;
    }

    /* Nút submit (Log in) full width, bo tròn */
    .auth-card .card-body .mt-4 button[type="submit"],
    .auth-card button[type="submit"]{
      width:100%; padding:.9rem 1rem; border-radius:.9rem;
      border:1px solid var(--primary); background:var(--primary);
      color:#fff; font-weight:600; box-shadow:0 6px 20px rgba(13,110,253,.18);
      transition:transform .05s ease, filter .15s ease;
    }
    .auth-card .card-body .mt-4 button[type="submit"]:hover{ filter:brightness(.98); }
    .auth-card .card-body .mt-4 button[type="submit"]:active{ transform:translateY(1px); }

    /* Link "Create an account" -> biến thành nút phụ tinh tế */
    .auth-card .card-body .mt-4 a{
      display:block; text-align:center; padding:.72rem 1rem;
      border:1px solid #dbe2ea; border-radius:.9rem; background:#fff;
      text-decoration:none; color:#0d47a1; font-weight:600;
    }
    .auth-card .card-body .mt-4 a:hover{
      background:#f8fbff; border-color:#cfe2ff; text-decoration:none;
    }
    .auth-card .card-body .mt-4 a:focus{ outline:none; box-shadow:0 0 0 .2rem rgba(13,110,253,.12); }

    /* "Forgot your password?" nhỏ hơn, cách khối trên 8-12px */
    .auth-card .card-body .mt-4 ~ a,
    .auth-card .card-body a[href*="password"]{
      display:block; text-align:center; margin-top:.8rem;
      color:#0d6efd; text-decoration:none; font-weight:500;
    }
    .auth-card .card-body a[href*="password"]:hover{ text-decoration:underline; }

    /* Dọn outline xanh xấu của trình duyệt cho anchors */
    .auth-card a{ background:transparent; }
    .auth-card a:focus{ outline:none; box-shadow:none; }
  </style>
</head>
<body>

  <div class="min-vh-100 d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 text-center mb-4">
          <a href="{{ url('/') }}" class="brand-link">
            <img src="{{ asset('images/logo-mark.svg') }}" alt="Logo">
            <span class="brand-name">{{ config('app.name', 'PhoneShop') }}</span>
          </a>
        </div>

        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
          <div class="card auth-card">
            <div class="card-body">
              {{ $slot }}
            </div>
          </div>

          <div class="text-center" style="color:#6b7280; font-size:.9rem; margin-top:1rem;">
            © {{ now()->year }} {{ config('app.name', 'PhoneShop') }}. All rights reserved.
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
