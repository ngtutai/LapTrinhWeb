@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')
    <div class="row g-4">
        {{-- SIDEBAR --}}
        <aside class="col-lg-3">
            <div class="card border-0 shadow-sm position-sticky" style="top: 88px;">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-3 text-center fw-semibold">
                        <i class="fa-solid fa-layer-group me-2"></i>Danh mục
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action rounded {{ !isset($category) ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            Tất cả
                        </a>
                        @foreach ($categories->sortBy('name') as $c)
                            <a class="list-group-item list-group-item-action rounded
                                  {{ isset($category) && $category->id == $c->id ? 'active' : '' }}"
                                href="{{ route('category.show', $c->slug) }}">
                                {{ $c->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <section class="col-lg-9">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <h5 class="mb-0 fw-semibold">Sản phẩm</h5>
                {{-- chỗ này để mở rộng sau (lọc/sắp xếp), hiện chỉ là khoảng trắng cân layout --}}
                <div class="small text-muted"></div>
            </div>

            {{-- Empty state --}}
            @if ($products->isEmpty())
                <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <div>Hiện tại chưa có sản phẩm nào trong danh mục này.</div>
                </div>
            @else
                <div class="row g-3 g-md-4">
                    @foreach ($products as $p)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm product-card">
                                <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                                    <img src="{{ $p->thumbnail ? asset('storage/' . $p->thumbnail) : 'https://placehold.co/600x600?text=Phone' }}"
                                        alt="{{ $p->name }}" class="w-100 h-100 object-fit-cover">
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-1 text-truncate fw-semibold" title="{{ $p->name }}">
                                        {{ $p->name }}
                                    </h6>
                                    <div class="mb-3">
                                        <span class="fw-bold text-danger fs-6">{{ number_format($p->price) }} đ</span>
                                    </div>

                                    <a href="{{ route('product.show', $p->slug) }}"
                                        class="btn btn-outline-primary mt-auto">
                                        <i class="fa-regular fa-eye me-1"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->withQueryString()->onEachSide(1)->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('styles')
    <style>
        /* Card hover nhẹ nhàng */
        .product-card {
            transition: transform .15s ease, box-shadow .15s ease;
            border-radius: 0.9rem;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
        }

        /* List-group bo góc đẹp hơn */
        .list-group .list-group-item {
            border: none;
            margin-bottom: .25rem;
            border-radius: .5rem !important;
        }

        .list-group .list-group-item.active {
            background: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
            border: 1px solid #cfe2ff;
        }

        /* Ảnh fit đẹp */
        .object-fit-cover {
            object-fit: cover;
        }

        /* Giảm “jump” khi sticky sidebar trên mobile */
        @media (max-width: 991.98px) {
            aside .position-sticky {
                position: static !important;
                top: auto !important;
            }
        }

        /* === Phân trang === */
        .pagination {
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination .page-link {
            border-radius: 6px;
            margin: 0 3px;
            color: #0d6efd;
            border-color: #dee2e6;
            transition: all 0.15s;
        }

        .pagination .page-link:hover {
            background-color: #e9f2ff;
            border-color: #bcd2ff;
            color: #0a58ca;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            font-weight: 600;
            box-shadow: 0 0 0 .15rem rgba(13, 110, 253, .25);
        }

        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
    </style>
@endpush
