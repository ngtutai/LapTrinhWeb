{{-- resources/views/partials/banner.blade.php --}}
@php
  // === Config có thể override khi include ===
  $mp4      = $mp4      ?? asset('videos/hero.mp4');            // public/videos/hero.mp4
  $webm     = $webm     ?? null;                                // ví dụ: asset('videos/hero.webm')
  $poster   = $poster   ?? asset('images/hero-poster.jpg');     // tuỳ chọn
  $height   = $height   ?? 'clamp(260px, 40vw, 560px)';         // chiều cao responsive
  $controls = $controls ?? false;                               // true = hiện controls (tắt autoplay)
  $caption  = $caption  ?? null;                                // tiêu đề overlay
  $sub      = $sub      ?? null;                                // phụ đề overlay
  $full     = $full     ?? true;                                // true = full ngang màn hình
  $navH     = $navH     ?? '72px';                              // chiều cao navbar để trừ
  $wrapperClasses = 'hero-video-wrapper position-relative overflow-hidden shadow-sm ';
  $wrapperClasses .= $full ? 'full-bleed overlap-navbar rounded-0 mb-4' : 'rounded-3 mb-4';
@endphp

<div class="{{ $wrapperClasses }}" style="--hero-h: {{ $height }}; --nav-h: {{ $navH }};">
  <video
    class="hero-video"
    @if($poster) poster="{{ $poster }}" @endif
    {{ $controls ? 'controls' : 'muted autoplay loop playsinline' }}
    id="heroVideo"
  >
    @if($webm)
      <source src="{{ $webm }}" type="video/webm">
    @endif
    <source src="{{ $mp4 }}" type="video/mp4">
    Trình duyệt của bạn không hỗ trợ video HTML5.
  </video>

  @if($caption)
    <div class="hero-caption position-absolute top-0 start-0 w-100 h-100 d-none d-md-flex align-items-end">
      <div class="w-100 p-3 p-md-4"
           style="background: linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,0));">
        <h5 class="text-white fw-semibold mb-1">{{ $caption }}</h5>
        @if($sub)
          <div class="text-white-50">{{ $sub }}</div>
        @endif
      </div>
    </div>
  @endif
</div>

@once('hero-video-styles')
@push('styles')
<style>
  /* Ngăn scroll ngang khi dùng full-bleed */
  body { overflow-x: hidden; }

  /* Full-bleed: bung ra 2 mép dù nằm trong .container */
  .full-bleed{
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
  }

  /* Để navbar fixed-top đè lên phần trên của hero (nếu cần) */
  .overlap-navbar{ margin-top: calc(-1 * var(--nav-h, 72px)); }

  /* Video thật sự full ngang + cao theo biến --hero-h */
  .hero-video{
    display: block;
    width: 100vw;                 /* full ngang */
    height: var(--hero-h, clamp(260px, 40vw, 560px));
    object-fit: cover;            /* crop cho đẹp */
  }

  /* Overlay tối nhẹ phía trên (tuỳ thích) */
  .hero-video-wrapper::before{
    content: "";
    position: absolute;
    inset: 0 0 auto 0;
    height: 80px;
    background: linear-gradient(to bottom, rgba(0,0,0,.55), rgba(0,0,0,0));
    pointer-events: none;
  }

  /* Ẩn vài control mặc định (tuỳ ý) */
  .hero-video::-webkit-media-controls-download-button { display: none; }
  .hero-video::-webkit-media-controls-fullscreen-button { display: none; }
</style>
@endpush
@endonce
