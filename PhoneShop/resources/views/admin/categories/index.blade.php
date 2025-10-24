@extends('layouts.app')
@section('title', 'Quản lý danh mục')

@section('content')
    <div class="row g-4">
        {{-- FORM TẠO MỚI (bên trái) --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header fw-bold">Tạo danh mục</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục</label>
                            <input name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <button class="btn btn-primary w-100">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- DANH SÁCH + Nút Sửa (mở Modal) / Xoá --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header fw-bold d-flex align-items-center justify-content-between">
                    <span>Danh sách danh mục</span>
                    <form class="d-none d-md-flex" method="get">
                        <input name="q" class="form-control form-control-sm" placeholder="Tìm theo tên..."
                            value="{{ request('q') }}">
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>Tên</th>
                                <th>Slug</th>
                                <th class="text-end" style="width:160px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $i => $c)
                                <tr>
                                    <td>{{ $categories->firstItem() + $i }}</td>
                                    <td>{{ $c->name }}</td>
                                    <td><code>{{ $c->slug }}</code></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary me-1 btn-edit"
                                            data-id="{{ $c->id }}" data-name="{{ $c->name }}"
                                            data-action="{{ route('admin.categories.update', $c) }}" data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            Sửa
                                        </button>

                                        <form method="post" action="{{ route('admin.categories.destroy', $c) }}"
                                            class="d-inline" onsubmit="return confirm('Xoá danh mục này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có danh mục</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($categories->hasPages())
                    <div class="card-footer">
                        {{ $categories->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL SỬA DANH MỤC --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" class="modal-content" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="form-text">Slug sẽ tự cập nhật theo tên khi lưu.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS nạp dữ liệu vào modal --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editModal = document.getElementById('editModal');
                const editForm = document.getElementById('editForm');
                const editName = document.getElementById('editName');

                // Khi bấm nút "Sửa"
                document.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const name = btn.getAttribute('data-name');
                        const action = btn.getAttribute('data-action');
                        editName.value = name || '';
                        editForm.setAttribute('action', action);
                    });
                });
            });
        </script>
    @endpush
@endsection
