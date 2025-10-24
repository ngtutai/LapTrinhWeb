@extends('layouts.app')
@section('title', 'Giỏ hàng')
@section('content')
    @if (empty($items))
        <p>Giỏ trống.</p>
    @else
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>SL</th>
                    <th>Thành tiền</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $row)
                    <tr>
                        <td>{{ $row['product']->name }}</td>
                        <td>{{ number_format($row['product']->price) }} đ</td>
                        <td>
                            <form method="post" action="{{ route('cart.update', $row['product']) }}" class="d-flex">
                                @csrf @method('PATCH')
                                <input type="number" name="qty" value="{{ $row['qty'] }}" min="0"
                                    class="form-control form-control-sm" style="width:90px">
                                <button class="btn btn-sm btn-outline-secondary ms-2">Cập nhật</button>
                            </form>
                        </td>
                        <td>{{ number_format($row['line']) }} đ</td>
                        <td>
                            <form method="post" action="{{ route('cart.remove', $row['product']) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-end h5">Tổng: {{ number_format($total) }} đ</div>
        @auth
            <a class="btn btn-success" href="{{ route('checkout.create') }}">Đặt hàng</a>
        @else
            <a class="btn btn-primary" href="{{ route('login') }}">Đăng nhập để đặt hàng</a>
        @endauth
    @endif
@endsection
