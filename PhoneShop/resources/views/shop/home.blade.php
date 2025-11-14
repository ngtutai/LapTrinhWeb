@extends('layouts.app')
@php($showBanner = true)

@section('title', $q ? "Kết quả: $q" : (isset($category) ? $category->name : 'Trang chủ'))

@section('content')
  <div class="row g-4">
    <section class="col-12">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
        <h5 class="mb-0 fw-semibold">
          @if(!empty($q))
            Kết quả cho: <span class="text-info">“{{ $q }}”</span>
            @if(isset($category)) <small class="text-muted">/ {{ $category->name }}</small> @endif
          @elseif(isset($category))
            {{ $category->name }}
          @else
            Sản phẩm
          @endif
        </h5>
        <div class="small text-muted">{{ $products->total() }} sản phẩm</div>
        <div class="container mb-3">
        <div class="d-flex flex-wrap gap-2 brand-chips">
          <button class="brand-chip active" data-brand="all">Tất cả</button>
          <button class="brand-chip" data-brand="iphone">iPhone</button>
          <button class="brand-chip" data-brand="samsung">Samsung</button>
          <button class="brand-chip" data-brand="xiaomi">Xiaomi</button>
          <button class="brand-chip" data-brand="oppo">OPPO</button>
        </div>
</div>

      </div>

      @if ($products->isEmpty())
        <div class="alert alert-warning">Không tìm thấy sản phẩm.</div>
      @else
        <div class="row g-3 g-md-4">
          @foreach ($products as $p)
            <div class="col-6 col-md-4 col-lg-3 product-card" data-brand="{{ strtolower($p->brand ?? 'other') }}">
              <div class="card h-100 border-0 shadow-sm">
                <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                  <img src="{{ $p->thumbnail ? asset('storage/'.$p->thumbnail) : asset('images/placeholder-product.jpg') }}"
                       class="w-100 h-100" style="object-fit:cover" alt="{{ $p->name }}">
                </div>
                <div class="card-body d-flex flex-column">
                  <h6 class="mb-1 text-truncate" title="{{ $p->name }}">{{ $p->name }}</h6>
                  <div class="mb-3">
                    <span class="fw-bold text-danger fs-6">{{ number_format($p->price, 0, ',', '.') }} đ</span>
                  </div>
                  <a href="{{ route('product.show', $p->slug) }}" class="btn btn-outline-primary mt-auto">Xem chi tiết</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">
          {{ $products->links() }}
        </div>
      @endif
    </section>
  </div>
  <script>
document.querySelectorAll('.brand-chip').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    document.querySelectorAll('.brand-chip').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');

    let brand = btn.dataset.brand;
    document.querySelectorAll('.product-card').forEach(card=>{
      if(brand==='all' || card.dataset.brand===brand){
        card.style.display='';
      } else {
        card.style.display='none';
      }
    });
  });
});
</script>

@endsection
