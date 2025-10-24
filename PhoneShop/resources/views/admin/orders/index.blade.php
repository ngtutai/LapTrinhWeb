@extends('layouts.app')
@section('title', 'QL Đơn hàng')
@section('content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Khách</th>
                <th>Tổng</th>
                <th>Trạng thái</th>
                <th>Ngày</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $o)
                <tr>
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->user->email ?? 'Guest' }}</td>
                    <td>{{ number_format($o->total) }} đ</td>
                    <td>{{ $o->status }}</td>
                    <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $o) }}">Xem</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->links() ?? '' }}
@endsection
