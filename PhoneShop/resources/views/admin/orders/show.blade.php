@extends('layouts.app')
@section('title', 'Chi tiết đơn #' . $order->id)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Đơn hàng #{{ $order->id }}</h4>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">← Quay lại</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header fw-bold">Thông tin chung</div>
                <div class="card-body">
                    <div class="mb-2"><strong>Khách:</strong> {{ $order->user->email ?? 'Guest' }}</div>
                    <div class="mb-2"><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <div class="mb-2"><strong>Trạng thái:</strong> <span
                            class="badge text-bg-secondary">{{ $order->status }}</span></div>
                    <div class="mb-2"><strong>Tổng tiền:</strong> {{ number_format($order->total) }} đ</div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header fw-bold">Địa chỉ giao hàng</div>
                <div class="card-body">
                    <div class="whitespace-pre-line">{{ $order->shipping_address ?: '—' }}</div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header fw-bold">Cập nhật trạng thái</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger mb-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{ route('admin.orders.update', $order) }}">
                        @csrf @method('PUT')
                        <div class="mb-2">
                            <select name="status" class="form-select" required>
                                @foreach (['pending', 'paid', 'shipped', 'cancelled'] as $st)
                                    <option value="{{ $st }}" @selected(old('status', $order->status) === $st)>{{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">Lưu trạng thái</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header fw-bold">Sản phẩm trong đơn</div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th class="text-end">Giá</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $i => $it)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $it->product->name ?? 'Sản phẩm đã xoá' }}</td>
                                    <td class="text-end">{{ number_format($it->price) }} đ</td>
                                    <td class="text-center">{{ $it->quantity }}</td>
                                    <td class="text-end">{{ number_format($it->price * $it->quantity) }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Tổng</th>
                                <th class="text-end">{{ number_format($order->total) }} đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
