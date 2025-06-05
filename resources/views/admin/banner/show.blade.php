@extends("layouts.admin.app")

@section("content")
  <main>
    <div class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
      <div class="container-fluid px-4">
        <div class="page-header-content">
          <div class="row align-items-center justify-content-between pt-3">
            <div class="col-auto mb-3">
              <h1 class="page-header-title">
                <div class="page-header-icon"><i data-feather="file-plus"></i></div>
                Produk
              </h1>
            </div>
            <div class="col-12 col-xl-auto mb-3">
              <a class="btn btn-sm btn-light text-primary" href="{{ route("banner.index") }}">
                <i class="me-1" data-feather="arrow-left"></i>
                Kembali ke Semua Spanduk
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container py-5">
      <div class="d-flex row mb-5">
        <div class="row justify-content-center">
          <div class="col col-12 col-md-6 col-lg-4">
            <div
              class="product-image mb-3 image d-flex justify-content-center ratio ratio-1x1 align-items-center bg-card rounded-3 overflow-hidden">
              <div class="wrapper d-flex justify-content-center align-items-center">
                <img src="{{ asset("storage/" . $banner->image) }}" style="background-position: center" alt=""
                  class="h-100 object-fit-contain" />
              </div>
            </div>
          </div>
          <div class="col-12 col-md-12 mt-4 mt-lg-0 col-lg-8">
            <h4>Judul</h4>
            <p class="lead mb-4">{{ $banner->title }}</p>
            <h4>Deskripsi</h4>
            <p class="lead mb-4">{{ $banner->description }}</p>
            @if ($banner->button_text)
              <h4>Teks Tombol</h4>
              <p class="lead mb-4">{{ $banner->button_text }}</p>
              <h4>Tautan Tombol</h4>
              <p class="lead mb-4">{{ $banner->button_link }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
