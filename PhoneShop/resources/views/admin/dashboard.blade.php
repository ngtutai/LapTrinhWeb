@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0 fw-semibold">
            <i class="fa-solid fa-gauge-high me-2"></i>Dashboard
        </h4>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left-long me-1"></i> Về cửa hàng
        </a>
    </div>

    {{-- KPI (ẩn nếu không có biến) --}}
    @if (isset($ordersToday) || isset($revenueToday) || isset($totalProducts) || isset($totalUsers))
        <div class="row g-3 g-md-4 mb-2">
            @isset($ordersToday)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 kpi-card">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Đơn hôm nay</div>
                            <div class="fs-4 fw-bold">{{ number_format($ordersToday) }}</div>
                            <div class="kpi-icon"><i class="fa-solid fa-receipt"></i></div>
                        </div>
                    </div>
                </div>
            @endisset

            @isset($revenueToday)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 kpi-card">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Doanh thu hôm nay</div>
                            <div class="fs-4 fw-bold text-danger">{{ number_format($revenueToday) }} ₫</div>
                            <div class="kpi-icon"><i class="fa-solid fa-coins"></i></div>
                        </div>
                    </div>
                </div>
            @endisset

            @isset($totalProducts)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 kpi-card">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Sản phẩm</div>
                            <div class="fs-4 fw-bold">{{ number_format($totalProducts) }}</div>
                            <div class="kpi-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                        </div>
                    </div>
                </div>
            @endisset

            @isset($totalUsers)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 kpi-card">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Người dùng</div>
                            <div class="fs-4 fw-bold">{{ number_format($totalUsers) }}</div>
                            <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                        </div>
                    </div>
                </div>
            @endisset
        </div>
    @endif

    {{-- Quick links --}}
    <div class="row g-3 g-md-4">
        <div class="col-md-4">
            <a class="text-decoration-none" href="{{ route('admin.categories.index') }}">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex">
                        <div class="icon-wrap me-3">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-semibold">Danh mục</h5>
                            <p class="text-muted mb-0">Quản lý danh mục sản phẩm</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a class="text-decoration-none" href="{{ route('admin.products.index') }}">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex">
                        <div class="icon-wrap me-3">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-semibold">Sản phẩm</h5>
                            <p class="text-muted mb-0">CRUD sản phẩm</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a class="text-decoration-none" href="{{ route('admin.orders.index') }}">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex">
                        <div class="icon-wrap me-3">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-semibold">Đơn hàng</h5>
                            <p class="text-muted mb-0">Xem & cập nhật trạng thái</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .kpi-card {
            border-radius: .9rem;
            position: relative;
            overflow: hidden;
        }

        .kpi-card .kpi-icon {
            position: absolute;
            right: .75rem;
            bottom: .5rem;
            opacity: .15;
            font-size: 2.5rem;
        }

        .hover-card {
            border-radius: .9rem;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
        }

        .icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f5ff;
            color: #0d6efd;
            flex: 0 0 44px;
        }
    </style>
@endpush
