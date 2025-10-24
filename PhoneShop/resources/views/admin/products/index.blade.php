@extends('layouts.app')
@section('title', 'Quản lý Sản phẩm')

@section('content')
    <div class="row g-3">
        {{-- THÔNG BÁO --}}
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- FORM TẠO MỚI + DANH SÁCH --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header fw-bold">Thêm sản phẩm</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm</label>
                            <input name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Giá (đ)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}"
                                    min="0" step="1" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Tồn kho</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}"
                                    min="0" required>
                            </div>
                        </div>
                        <div class="mb-3 mt-2">
                            <label class="form-label">Thương hiệu</label>
                            <input name="brand" class="form-control" value="{{ old('brand') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ảnh (jpg/png ≤ 2MB)</label>
                            <input type="file" name="thumbnail" id="createThumbnail" class="form-control"
                                accept=".jpg,.jpeg,.png">

                            {{-- Ảnh xem trước --}}
                            <div class="mt-2">
                                <img id="createPreview" src="https://placehold.co/200x200?text=Preview" alt="Xem trước ảnh"
                                    class="img-thumbnail rounded" style="max-width:100px;">
                            </div>
                        </div>

                        <button class="btn btn-primary w-100">Lưu</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Danh sách sản phẩm</span>
                    {{-- (tuỳ chọn) thanh tìm kiếm --}}
                    <form method="get" class="d-flex">
                        <input name="q" class="form-control form-control-sm me-2" placeholder="Tìm theo tên..."
                            value="{{ request('q') }}">
                    </form>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width:70px">Ảnh</th>
                                <th>Tên</th>
                                <th>Danh mục</th>
                                <th class="text-end">Giá</th>
                                <th class="text-center">Kho</th>
                                <th class="text-end" style="width:200px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $p)
                                <tr>
                                    <td>{{ ($products->firstItem() ?? 0) + $loop->index }}</td>
                                    <td>
                                        <div class="ratio ratio-1x1 bg-light rounded overflow-hidden" style="width:60px;">
                                            <img src="{{ $p->thumbnail ? asset('storage/' . $p->thumbnail) : 'https://placehold.co/60x60?text=Phone' }}"
                                                alt="{{ $p->name }}" class="object-fit-cover w-100 h-100"
                                                style="object-fit: cover;">
                                        </div>
                                    </td>

                                    <td class="fw-semibold">{{ $p->name }}</td>
                                    <td>{{ $p->category->name ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($p->price) }} đ</td>
                                    <td class="text-center">{{ $p->stock }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#editModal" data-id="{{ $p->id }}"
                                            data-name="{{ $p->name }}" data-category-id="{{ $p->category_id }}"
                                            data-price="{{ $p->price }}" data-stock="{{ $p->stock }}"
                                            data-brand="{{ $p->brand }}"
                                            data-thumb="{{ $p->thumbnail ? asset('storage/' . $p->thumbnail) : '' }}"
                                            data-update-url="{{ route('admin.products.update', $p) }}">Sửa</button>

                                        <form class="d-inline" method="post"
                                            action="{{ route('admin.products.destroy', $p) }}"
                                            onsubmit="return confirm('Xoá sản phẩm này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Chưa có sản phẩm</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (method_exists($products, 'links'))
                    <div class="card-footer">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL SỬA SẢN PHẨM --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="post" id="editForm" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên sản phẩm</label>
                            <input name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" id="editCategory" class="form-select" required>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá (đ)</label>
                            <input type="number" name="price" id="editPrice" class="form-control" min="0"
                                step="1000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tồn kho</label>
                            <input type="number" name="stock" id="editStock" class="form-control" min="0"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Thương hiệu</label>
                            <input name="brand" id="editBrand" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label d-block">Ảnh hiện tại</label>
                            <img id="editThumbPreview" src="" class="img-thumbnail mb-2"
                                style="max-width:240px; display:none;">
                            <input type="file" name="thumbnail" class="form-control" accept=".jpg,.jpeg,.png">
                            <small class="text-muted">Để trống nếu không đổi ảnh.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS: đổ dữ liệu vào modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const btn = event.relatedTarget;
                const url = btn.getAttribute('data-update-url');
                const name = btn.getAttribute('data-name');
                const catId = btn.getAttribute('data-category-id');
                const price = btn.getAttribute('data-price');
                const stock = btn.getAttribute('data-stock');
                const brand = btn.getAttribute('data-brand') || '';
                const thumb = btn.getAttribute('data-thumb');

                const form = document.getElementById('editForm');
                form.action = url;

                document.getElementById('editName').value = name || '';
                document.getElementById('editCategory').value = catId || '';
                document.getElementById('editPrice').value = price || 0;
                document.getElementById('editStock').value = stock || 0;
                document.getElementById('editBrand').value = brand;

                const img = document.getElementById('editThumbPreview');
                if (thumb) {
                    img.src = thumb;
                    img.style.display = 'inline-block';
                } else {
                    img.removeAttribute('src');
                    img.style.display = 'none';
                }
            });

            // clear file input khi đóng modal (tránh giữ file cũ)
            editModal.addEventListener('hidden.bs.modal', function() {
                const fileInputs = editModal.querySelectorAll('input[type="file"]');
                fileInputs.forEach(i => i.value = '');
            });
        });
    </script>

    {{-- JS: Xem trước ảnh khi tạo mới --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('createThumbnail');
            const preview = document.getElementById('createPreview');

            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result; // gán ảnh đã chọn vào thẻ img
                    };
                    reader.readAsDataURL(file); // đọc file thành base64 để hiển thị
                } else {
                    // nếu bỏ chọn file thì quay về ảnh mặc định
                    preview.src = 'https://placehold.co/200x200?text=Preview';
                }
            });
        });
    </script>

@endsection
