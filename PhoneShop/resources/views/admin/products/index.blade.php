@extends('layouts.app')
@section('title', 'Quản lý Sản phẩm')

@php
  // Lấy preset từ config
  $phonePresets = config('phone_presets.brands', []);
@endphp

@section('content')
<div class="row g-3">
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

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
  </div>

  {{-- CREATE --}}
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header fw-bold">Thêm sản phẩm</div>
      <div class="card-body">
        <form method="post"
              action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              id="createProductForm">
          @csrf

          {{-- CHỌN MẪU ĐIỆN THOẠI CÓ SẴN TỪ CONFIG --}}
          @if(!empty($phonePresets))
            <div class="mb-3">
              <label class="form-label">Chọn mẫu có sẵn</label>
              <div class="row g-2">
                <div class="col-6">
                  <select id="presetBrand" class="form-select form-select-sm">
                    <option value="">-- Thương hiệu mẫu --</option>
                    @foreach($phonePresets as $key => $brand)
                      <option value="{{ $key }}">{{ $brand['label'] ?? ucfirst($key) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-6">
                  <select id="presetModel" class="form-select form-select-sm" disabled>
                    <option value="">-- Mẫu máy --</option>
                  </select>
                </div>
              </div>
              <small class="text-muted">
                Chọn thương hiệu &gt; chọn mẫu máy để tự động điền Tên, Danh mục, Giá, Thương hiệu và Thông số kỹ thuật.
              </small>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input name="name" class="form-control" value="{{ old('name') }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-select" required>
              <option value="">-- Chọn danh mục --</option>
              @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">Giá (đ)</label>
              <input type="number" name="price" class="form-control"
                     value="{{ old('price') }}" min="0" step="1000" required>
            </div>
            <div class="col-6">
              <label class="form-label">Tồn kho</label>
              <input type="number" name="stock" class="form-control"
                     value="{{ old('stock', 0) }}" min="0" required>
            </div>
          </div>

          <div class="mb-2 mt-2">
            <label class="form-label">Thương hiệu</label>
            <input name="brand" class="form-control" value="{{ old('brand') }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Ảnh (jpg/png/webp ≤ 4MB)</label>
            <input type="file" name="thumbnail" id="createThumbnail"
                   class="form-control" accept=".jpg,.jpeg,.png,.webp">
            <div class="mt-2">
              <img id="createPreview"
                   src="https://placehold.co/200x200?text=Preview"
                   class="img-thumbnail rounded"
                   style="max-width:120px;">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Thông số kỹ thuật</label>
            <div class="row g-2">
              @foreach (['RAM','ROM','Screen','CPU','Camera','Battery','OS','SIM','Weight'] as $k)
                <div class="col-12 col-md-6">
                  <input name="specs[{{ $k }}]"
                         class="form-control form-control-sm"
                         placeholder="{{ $k }}"
                         value="{{ old('specs.'.$k) }}">
                </div>
              @endforeach
            </div>
          </div>

          <button class="btn btn-primary w-100">Lưu</button>
        </form>
      </div>
    </div>
  </div>

  {{-- LIST --}}
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">Danh sách sản phẩm</span>
        <form method="get" class="d-flex">
          <input name="q" class="form-control form-control-sm"
                 placeholder="Tìm theo tên..." value="{{ request('q') }}">
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
                  <img src="{{ $p->thumbnail ? asset('storage/'.$p->thumbnail) : 'https://placehold.co/60x60?text=Phone' }}"
                       alt="{{ $p->name }}" class="w-100 h-100" style="object-fit:cover;">
                </div>
              </td>
              <td class="fw-semibold text-truncate" style="max-width:220px"
                  title="{{ $p->name }}">{{ $p->name }}</td>
              <td>{{ $p->category->name ?? '-' }}</td>
              <td class="text-end">{{ number_format($p->price) }} đ</td>
              <td class="text-center">{{ $p->stock }}</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="modal" data-bs-target="#editModal"
                        data-update-url="{{ route('admin.products.update', $p) }}"
                        data-name="{{ $p->name }}"
                        data-category-id="{{ $p->category_id }}"
                        data-price="{{ $p->price }}"
                        data-stock="{{ $p->stock }}"
                        data-brand="{{ $p->brand }}"
                        data-thumb="{{ $p->thumbnail ? asset('storage/'.$p->thumbnail) : '' }}"
                        data-spec='@json($p->specs ?? [])'>
                  Sửa
                </button>

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
              <td colspan="7" class="text-center text-muted py-4">Chưa có sản phẩm</td>
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

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="post" id="editForm" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Sửa sản phẩm</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">

        {{-- CHỌN MẪU CÓ SẴN CHO PHẦN SỬA --}}
        @if(!empty($phonePresets))
          <div class="mb-3">
            <label class="form-label">Chọn mẫu có sẵn (Sửa)</label>
            <div class="row g-2">
              <div class="col-6">
                <select id="editPresetBrand" class="form-select form-select-sm">
                  <option value="">-- Thương hiệu mẫu --</option>
                  @foreach($phonePresets as $key => $brand)
                    <option value="{{ $key }}">{{ $brand['label'] ?? ucfirst($key) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-6">
                <select id="editPresetModel" class="form-select form-select-sm" disabled>
                  <option value="">-- Mẫu máy --</option>
                </select>
              </div>
            </div>
            <small class="text-muted">
              Chọn thương hiệu &gt; chọn mẫu máy để tự động điền lại Tên, Giá, Thương hiệu, Danh mục và Thông số kỹ thuật.
            </small>
          </div>
        @endif

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
            <input type="number" name="price" id="editPrice"
                   class="form-control" min="0" step="1000" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tồn kho</label>
            <input type="number" name="stock" id="editStock"
                   class="form-control" min="0" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Thương hiệu</label>
            <input name="brand" id="editBrand" class="form-control">
          </div>

          <div class="col-md-12">
            <label class="form-label d-block">Ảnh hiện tại</label>
            <img id="editThumbPreview" src=""
                 class="img-thumbnail mb-2"
                 style="max-width:240px; display:none;">
            <input type="file" name="thumbnail" class="form-control"
                   accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">Để trống nếu không đổi ảnh.</small>
          </div>

          <div class="col-12">
            <label class="form-label">Thông số kỹ thuật</label>
            <div class="row g-2">
              @foreach (['RAM','ROM','Screen','CPU','Camera','Battery','OS','SIM','Weight'] as $k)
                <div class="col-12 col-md-6">
                  <input name="specs[{{ $k }}]"
                         class="form-control form-control-sm"
                         placeholder="{{ $k }}">
                </div>
              @endforeach
            </div>
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
@endsection



@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ===== LẤY DỮ LIỆU PRESET TRỰC TIẾP TỪ BLADE =====
  // @json($phonePresets) sẽ render thành object JS: { apple: {...}, samsung: {...}, ... }
  var phonePresets = @json($phonePresets) || {};

  // ===== PREVIEW ẢNH KHI TẠO MỚI =====
  var createInput  = document.getElementById('createThumbnail');
  var createPreview = document.getElementById('createPreview');
  if (createInput && createPreview) {
    createInput.addEventListener('change', function (e) {
      var file = e.target.files && e.target.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function (ev) {
          createPreview.src = ev.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        createPreview.src = 'https://placehold.co/200x200?text=Preview';
      }
    });
  }

  // ===== EDIT MODAL: ĐỔ DỮ LIỆU HIỆN TẠI =====
  var editModal  = document.getElementById('editModal');
  var editFormEl = document.getElementById('editForm');

  if (editModal && editFormEl) {
    editModal.addEventListener('show.bs.modal', function (event) {
      var btn  = event.relatedTarget;
      var form = editFormEl;

      form.action = btn.getAttribute('data-update-url');
      document.getElementById('editName').value     = btn.getAttribute('data-name') || '';
      document.getElementById('editCategory').value = btn.getAttribute('data-category-id') || '';
      document.getElementById('editPrice').value    = btn.getAttribute('data-price') || 0;
      document.getElementById('editStock').value    = btn.getAttribute('data-stock') || 0;
      document.getElementById('editBrand').value    = btn.getAttribute('data-brand') || '';

      // Reset preset trong modal sửa
      var editPresetBrand = document.getElementById('editPresetBrand');
      var editPresetModel = document.getElementById('editPresetModel');
      if (editPresetBrand && editPresetModel) {
        editPresetBrand.value = '';
        editPresetModel.innerHTML = '<option value="">-- Mẫu máy --</option>';
        editPresetModel.disabled = true;
      }

      // thumb
      var img   = document.getElementById('editThumbPreview');
      var thumb = btn.getAttribute('data-thumb');
      if (thumb) {
        img.src = thumb;
        img.style.display = 'inline-block';
      } else {
        img.removeAttribute('src');
        img.style.display = 'none';
      }

      // specs từ data-spec
      var specRaw = btn.getAttribute('data-spec') || '{}';
      var spec = {};
      try { spec = JSON.parse(specRaw); } catch (e) { spec = {}; }

      var inputs = editModal.querySelectorAll('[name^="specs["]');
      inputs.forEach(function (inp) {
        var key = inp.name.replace(/^specs\[(.+)\]$/, '$1');
        var val = (spec && Object.prototype.hasOwnProperty.call(spec, key)) ? spec[key] : '';
        inp.value = (val !== null && val !== undefined) ? val : '';
      });
    });

    editModal.addEventListener('hidden.bs.modal', function () {
      var files = editModal.querySelectorAll('input[type="file"]');
      files.forEach(function (i) { i.value = ''; });
    });
  }

  // ===== AUTO ĐIỀN FORM TẠO MỚI TỪ PRESET =====
  var presetBrand = document.getElementById('presetBrand');
  var presetModel = document.getElementById('presetModel');
  var createForm  = document.getElementById('createProductForm');

  if (presetBrand && presetModel && createForm) {

    // Chọn thương hiệu mẫu -> đổ danh sách mẫu
    presetBrand.addEventListener('change', function () {
      var brandKey = this.value;
      presetModel.innerHTML = '<option value="">-- Mẫu máy --</option>';
      presetModel.disabled = !brandKey;

      if (!brandKey || !phonePresets[brandKey] || !phonePresets[brandKey].models) {
        return;
      }

      var models = phonePresets[brandKey].models;
      for (var modelKey in models) {
        if (!Object.prototype.hasOwnProperty.call(models, modelKey)) continue;
        var model = models[modelKey];

        var opt = document.createElement('option');
        opt.value = modelKey;
        opt.text  = model.name || modelKey;
        presetModel.appendChild(opt);
      }
    });

    // Chọn mẫu -> auto fill form CREATE
    presetModel.addEventListener('change', function () {
      var brandKey = presetBrand.value;
      var modelKey = this.value;

      if (!brandKey || !modelKey ||
          !phonePresets[brandKey] ||
          !phonePresets[brandKey].models ||
          !phonePresets[brandKey].models[modelKey]) {
        return;
      }

      var tpl = phonePresets[brandKey].models[modelKey];

      var nameInput  = createForm.querySelector('input[name="name"]');
      var brandInput = createForm.querySelector('input[name="brand"]');
      var priceInput = createForm.querySelector('input[name="price"]');
      var stockInput = createForm.querySelector('input[name="stock"]');
      var catSelect  = createForm.querySelector('select[name="category_id"]');

      if (nameInput)  nameInput.value  = tpl.name || '';
      if (brandInput) brandInput.value = tpl.brand_label || (phonePresets[brandKey] && phonePresets[brandKey].label) || '';
      if (priceInput && typeof tpl.price !== 'undefined') priceInput.value = tpl.price;

      if (stockInput && !stockInput.value) stockInput.value = tpl.default_stock || 0;

      if (catSelect && tpl.category_name) {
        var targetText = String(tpl.category_name).toLowerCase();
        var opts = Array.prototype.slice.call(catSelect.options);
        var found = opts.find(function (o) {
          return String(o.text).toLowerCase().indexOf(targetText) !== -1;
        });
        if (found) {
          catSelect.value = found.value;
        }
      }

      var specs = tpl.specs || {};
      var specInputs = createForm.querySelectorAll('[name^="specs["]');
      specInputs.forEach(function (inp) {
        var key = inp.name.replace(/^specs\[(.+)\]$/, '$1');
        var val = (specs && Object.prototype.hasOwnProperty.call(specs, key)) ? specs[key] : '';
        inp.value = (val !== null && val !== undefined) ? val : '';
      });
    });
  }

  // ===== AUTO ĐIỀN FORM SỬA TỪ PRESET =====
  var editPresetBrand = document.getElementById('editPresetBrand');
  var editPresetModel = document.getElementById('editPresetModel');

  if (editPresetBrand && editPresetModel && editFormEl) {

    editPresetBrand.addEventListener('change', function () {
      var brandKey = this.value;
      editPresetModel.innerHTML = '<option value="">-- Mẫu máy --</option>';
      editPresetModel.disabled = !brandKey;

      if (!brandKey || !phonePresets[brandKey] || !phonePresets[brandKey].models) {
        return;
      }

      var models = phonePresets[brandKey].models;
      for (var modelKey in models) {
        if (!Object.prototype.hasOwnProperty.call(models, modelKey)) continue;
        var model = models[modelKey];

        var opt = document.createElement('option');
        opt.value = modelKey;
        opt.text  = model.name || modelKey;
        editPresetModel.appendChild(opt);
      }
    });

    editPresetModel.addEventListener('change', function () {
      var brandKey = editPresetBrand.value;
      var modelKey = this.value;

      if (!brandKey || !modelKey ||
          !phonePresets[brandKey] ||
          !phonePresets[brandKey].models ||
          !phonePresets[brandKey].models[modelKey]) {
        return;
      }

      var tpl = phonePresets[brandKey].models[modelKey];

      var nameInput  = document.getElementById('editName');
      var brandInput = document.getElementById('editBrand');
      var priceInput = document.getElementById('editPrice');
      var stockInput = document.getElementById('editStock');
      var catSelect  = document.getElementById('editCategory');

      if (nameInput)  nameInput.value  = tpl.name || '';
      if (brandInput) brandInput.value = tpl.brand_label || (phonePresets[brandKey] && phonePresets[brandKey].label) || '';
      if (priceInput && typeof tpl.price !== 'undefined') priceInput.value = tpl.price;

      // Nếu muốn giữ tồn kho cũ thì bỏ qua; muốn reset theo default_stock thì dùng:
      // if (stockInput) stockInput.value = tpl.default_stock || 0;

      if (catSelect && tpl.category_name) {
        var targetText = String(tpl.category_name).toLowerCase();
        var opts = Array.prototype.slice.call(catSelect.options);
        var found = opts.find(function (o) {
          return String(o.text).toLowerCase().indexOf(targetText) !== -1;
        });
        if (found) {
          catSelect.value = found.value;
        }
      }

      var specs = tpl.specs || {};
      var specInputs = editFormEl.querySelectorAll('[name^="specs["]');
      specInputs.forEach(function (inp) {
        var key = inp.name.replace(/^specs\[(.+)\]$/, '$1');
        var val = (specs && Object.prototype.hasOwnProperty.call(specs, key)) ? specs[key] : '';
        inp.value = (val !== null && val !== undefined) ? val : '';
      });
    });
  }

});
</script>
@endpush

