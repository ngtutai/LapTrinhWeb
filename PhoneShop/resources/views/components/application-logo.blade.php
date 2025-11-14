{{-- resources/views/components/application-logo.blade.php --}}
<div {{ $attributes->merge([
    'class' => 'd-inline-flex align-items-center justify-content-center rounded-circle'
]) }}
     style="
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg,#0d6efd,#20c997);
        box-shadow: 0 .45rem 1.2rem rgba(13,110,253,.45);
     ">
    <span class="fw-bold text-uppercase"
          style="font-size: .9rem; letter-spacing: .22em; color:#fff;">
        TCT
    </span>
</div>
