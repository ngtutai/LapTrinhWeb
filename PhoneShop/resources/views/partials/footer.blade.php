<footer id="site-footer" class="text-light pt-5" style="background:#0b1320; color:#fff;">
  <div class="container pb-4">
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <a href="{{ route('home') }}" class="d-inline-flex align-items-center mb-3 text-decoration-none" style="color:#fff;">
          <img src="{{ asset('images/logo-mark-white.svg') }}" alt="PhoneShop" height="28" class="me-2">
          <span class="fw-semibold">PhoneShop</span>
        </a>
        <p class="mt-2 mb-3 small" style="color:rgba(255,255,255,.7);">
          Cửa hàng điện thoại & phụ kiện chính hãng. Giao nhanh, giá tốt, hỗ trợ đổi trả linh hoạt.
        </p>
        <div class="d-flex gap-2">
          <a class="btn btn-outline-light btn-sm rounded-circle" style="height:36px;width:36px;" href="#"><i class="bi bi-facebook"></i></a>
          <a class="btn btn-outline-light btn-sm rounded-circle" style="height:36px;width:36px;" href="#"><i class="bi bi-instagram"></i></a>
          <a class="btn btn-outline-light btn-sm rounded-circle" style="height:36px;width:36px;" href="#"><i class="bi bi-youtube"></i></a>
          <a class="btn btn-outline-light btn-sm rounded-circle" style="height:36px;width:36px;" href="#"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      <div class="col-6 col-md-2">
        <h6 class="fw-semibold mb-3">Liên kết</h6>
        <ul class="list-unstyled small mb-0">
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="{{ route('home') }}">Trang chủ</a></li>
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="{{ route('home') }}?q=">Sản phẩm</a></li>
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="{{ route('cart.index') }}">Giỏ hàng</a></li>
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="{{ route('checkout.create') }}">Thanh toán</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3">
        <h6 class="fw-semibold mb-3">Hỗ trợ</h6>
        <ul class="list-unstyled small mb-0">
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="#">Chính sách bảo hành</a></li>
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="#">Đổi trả & hoàn tiền</a></li>
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="#">Giao hàng & thanh toán</a></li>
          <li><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="#">Câu hỏi thường gặp</a></li>
        </ul>
      </div>

      <div class="col-12 col-md-3">
        <h6 class="fw-semibold mb-3">Liên hệ</h6>
        <ul class="list-unstyled small mb-3" style="color:rgba(255,255,255,.7);">
          <li class="mb-1"><i class="bi bi-geo-alt me-2"></i>123 Trần Phú, Q.5, TP.HCM</li>
          <li class="mb-1"><i class="bi bi-telephone me-2"></i><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="tel:0123456789">0123 456 789</a></li>
          <li class="mb-1"><i class="bi bi-envelope me-2"></i><a class="text-decoration-none" style="color:rgba(255,255,255,.8);" href="mailto:support@phoneshop.vn">support@phoneshop.vn</a></li>
        </ul>

        <form method="post" action="#" class="needs-validation" novalidate>
          @csrf
          <div class="input-group">
            <input type="email" class="form-control form-control-sm" placeholder="Nhập email nhận ưu đãi" required>
            <button class="btn btn-sm btn-primary">Đăng ký</button>
          </div>
          <div class="form-text" style="color:rgba(255,255,255,.6);">Bạn có thể hủy bất cứ lúc nào.</div>
        </form>
      </div>
    </div>
  </div>

  <div style="border-top:1px solid rgba(255,255,255,.15);">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between py-3 gap-2">
      <div class="small" style="color:rgba(255,255,255,.6);">© {{ now()->year }} PhoneShop. All rights reserved.</div>
      <div class="d-flex align-items-center gap-3 small">
        <a href="#" class="text-decoration-none" style="color:rgba(255,255,255,.8);">Điều khoản</a>
        <a href="#" class="text-decoration-none" style="color:rgba(255,255,255,.8);">Bảo mật</a>
        <a href="#" class="text-decoration-none" style="color:rgba(255,255,255,.8);">Liên hệ</a>
      </div>
    </div>
  </div>
</footer>
