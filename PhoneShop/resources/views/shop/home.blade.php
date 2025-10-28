@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')
{{-- cao hơn một chút --}}
@include('partials.banner', [
  'caption' => 'Siêu ưu đãi tháng này',
  'sub' => 'Giảm đến 50%',
  'height' => 'clamp(320px, 50vw, 720px)'   // trước là clamp(260px, 40vw, 560px)
])


  <div class="row g-4">
    <section class="col-12">
      {{-- Header --}}
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
        <h5 class="mb-0 fw-semibold">Sản phẩm</h5>
        <div class="small text-muted"></div>
      </div>

      {{-- ==== Thanh danh mục ngang (chips) ==== --}}
      @php
        $cats = collect($categories ?? [])->sortBy('name');
      @endphp
      @if($cats->count())
        <div class="cat-bar d-flex align-items-center gap-2 overflow-auto pb-2 mb-3">
          {{-- Tất cả --}}
          <a href="{{ route('home') }}"
             class="btn btn-sm rounded-pill {{ empty($category) ? 'btn-primary text-white' : 'btn-outline-secondary' }}">
            Tất cả
          </a>

          {{-- Các danh mục --}}
          @foreach($cats as $c)
            <a href="{{ route('category.show', $c->slug) }}"
               class="btn btn-sm rounded-pill {{ (!empty($category) && $category->id === $c->id) ? 'btn-primary text-white' : 'btn-outline-secondary' }}">
              {{ $c->name }}
            </a>
          @endforeach
        </div>
      @endif

      {{-- Empty state --}}
      @if (empty($products) || $products->isEmpty())
        <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i>
          <div>Hiện tại chưa có sản phẩm nào.</div>
        </div>
      @else
        {{-- Lưới sản phẩm --}}
        <div class="row g-3 g-md-4">
          @foreach ($products as $p)
            <div class="col-6 col-md-4 col-lg-3">
              <div class="card h-100 border-0 shadow-sm product-card">
                <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                  <img
                    src="{{ $p->thumbnail ? asset('storage/' . $p->thumbnail) : 'https://placehold.co/600x600?text=Phone' }}"
                    alt="{{ $p->name }}"
                    class="w-100 h-100 object-fit-cover">
                </div>

                <div class="card-body d-flex flex-column">
                  <h6 class="card-title mb-1 text-truncate fw-semibold" title="{{ $p->name }}">
                    {{ $p->name }}
                  </h6>
                  <div class="mb-3">
                    <span class="fw-bold text-danger fs-6">{{ number_format($p->price) }} đ</span>
                  </div>

                  <a href="{{ route('product.show', $p->slug) }}" class="btn btn-outline-primary mt-auto">
                    <i class="bi bi-eye me-1"></i> Xem chi tiết
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- Phân trang --}}
        <div class="mt-4 d-flex justify-content-center">
          {{ $products->withQueryString()->onEachSide(1)->links() }}
        </div>
      @endif
    </section>
  </div>
@endsection

@push('styles')
<style>
  /* Thanh danh mục ngang */
  .cat-bar{ -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
  .cat-bar::-webkit-scrollbar{ height:6px; }
  .cat-bar::-webkit-scrollbar-thumb{ background:#e2e6ea; border-radius:4px; }
  .cat-bar .btn{ white-space:nowrap; padding:.45rem .9rem; border-width:1px; }

  /* Card hover */
  .product-card{ transition:transform .15s ease, box-shadow .15s ease; border-radius:.9rem; }
  .product-card:hover{ transform:translateY(-2px); box-shadow:0 .5rem 1rem rgba(0,0,0,.08) !important; }

  .object-fit-cover{ object-fit:cover; }

  /* Phân trang */
  .pagination{ justify-content:center; margin-top:1rem; }
  .pagination .page-link{ border-radius:6px; margin:0 3px; color:#0d6efd; border-color:#dee2e6; transition:all .15s; }
  .pagination .page-link:hover{ background:#e9f2ff; border-color:#bcd2ff; color:#0a58ca; }
  .pagination .page-item.active .page-link{ background:#0d6efd; border-color:#0d6efd; color:#fff; font-weight:600; box-shadow:0 0 0 .15rem rgba(13,110,253,.25); }
  .pagination .page-item.disabled .page-link{ color:#adb5bd; background:#f8f9fa; border-color:#dee2e6; }
</style>
@endpush
