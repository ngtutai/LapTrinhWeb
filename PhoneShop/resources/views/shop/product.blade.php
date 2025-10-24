@extends('layouts.app')
@section('title', $product->name)
@section('content')
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <img class="card-img-top rounded-top"
                    src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/800x600?text=Phone' }}"
                    alt="{{ $product->name }}">
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <h3 class="fw-bold">{{ $product->name }}</h3>
                <div class="h4 text-danger fw-bold mb-3">{{ number_format($product->price) }} đ</div>

                <p class="text-muted mb-2">
                    <i class="bi bi-box-seam me-1"></i> Kho:
                    <strong>{{ $product->stock > 0 ? $product->stock : 'Hết hàng' }}</strong>
                </p>

                @if ($product->brand)
                    <p><i class="bi bi-tags me-1"></i> Thương hiệu: <strong>{{ $product->brand }}</strong></p>
                @endif

                @if ($product->specs)
                    <div class="bg-light rounded p-3 mb-3">
                        <h6 class="text-secondary mb-2">Thông số kỹ thuật:</h6>
                        <pre class="mb-0 text-dark small">{{ json_encode($product->specs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif

                @if ($product->stock > 0)
                    <form method="post" action="{{ route('cart.add', $product) }}" class="mt-3">
                        @csrf
                        <div class="input-group mb-3" style="max-width:250px;">
                            <span class="input-group-text bg-secondary text-white fw-bold">SL</span>
                            <input type="number" name="qty" value="1" min="1"
                                class="form-control text-center">
                            <button class="btn btn-success fw-semibold">
                                <i class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning d-inline-flex align-items-center mt-3" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2 fs-4 text-warning"></i>
                        Sản phẩm đã
                        <strong class="ms-1"> hết
                            hàng</strong>.
                    </div>
                    <button class="btn btn-secondary mt-2" disabled>
                        <i class="bi bi-cart-x"></i> Không thể thêm vào giỏ
                    </button>
                @endif

                <a href="{{ route('home') }}" class="btn btn-outline-primary mt-2">
                    <i class="fa-solid fa-left-long me-2"></i>Quay lại cửa hàng
                </a>
            </div>
        </div>
    </div>
@endsection
