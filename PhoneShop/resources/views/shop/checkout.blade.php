@extends('layouts.app')
@section('title', 'Thanh toán')
@section('content')
    <form method="post" action="{{ route('checkout.store') }}" class="card p-3">
        @csrf
        <div class="mb-3">
            <label class="form-label">Địa chỉ giao hàng</label>
            <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
        </div>
        <button class="btn btn-success">Xác nhận đặt hàng</button>
    </form>
@endsection
