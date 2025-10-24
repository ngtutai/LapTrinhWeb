@extends('layouts.app')
@section('title', 'Đơn hàng của tôi')

@section('content')
    <h4 class="mb-3">Đơn hàng của tôi</h4>

    @if ($orders->isEmpty())
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
        <a class="btn btn-primary" href="{{ route('home') }}">Tiếp tục mua sắm</a>
    @else
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ngày</th>
                    <th>Tổng</th>
                    <th>Trạng thái</th>
                    <th>Sản phẩm</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $o)
                    <tr>
                        <td>{{ $o->id }}</td>
                        <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($o->total) }} đ</td>
                        <td><span class="badge text-bg-secondary">{{ $o->status }}</span></td>
                        <td>
                            @foreach ($o->items as $it)
                                <div>
                                    {{ $it->product->name ?? 'Sản phẩm đã xoá' }}
                                    × {{ $it->quantity }}
                                    — {{ number_format($it->price * $it->quantity) }} đ
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    @endif
@endsection
