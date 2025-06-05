@props(["products" => [], "type" => "default", "name" => "Produk"])
@php
  $id = str_replace(" ", "_", strtolower($name));
@endphp

@if ($type === "slider")
  <section id="daftar_produk">
    <div class="container py-5">
      <div class="d-flex justify-content-between flex-column flex-md-row gap-2">
        <h2 class="section-title mb-0">{{ $name }}</h2>
        <div class="d-flex align-items-center justify-content-between gap-4">
          <a href="{{ route("search") }}"
            class="text-decoration-none text-secondary fw-semibold d-flex align-items-center gap-2">Lihat
            semua <i class="fa-solid fa-arrow-right"></i></a>

          <div class="d-flex" style="gap: 8px">
            <div id="{{ $id }}_product_button_prev">
              <div class="bg-secondary-subtle btn rounded-2 text-dark d-flex align-items-center justify-content-center"
                style="width: 38px; height: 38px; ">
                <i class="fa-solid fa-angle-left"></i>
              </div>
            </div>
            <div id="{{ $id }}_product_button_next">
              <div class="bg-secondary-subtle rounded-2 btn text-dark d-flex align-items-center justify-content-center"
                style="width: 38px; height: 38px; ">
                <i class="fa-solid fa-angle-right"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper py-5 px-3 px-md-0" id="{{ $id }}_product_swiper">
          @if (count($products) > 0)
        <div class="swiper-wrapper w-100 h-100">
            @foreach ($products as $product)
              <div class="swiper-slide">
                <x-product-item :product="$product" />
              </div>
            @endforeach
        </div>
        @else
          <div>
            <div class="text-center border-0">
              <img src="/assets/img/illustrations/404-error.svg" alt="No Orders" class="img-fluid mb-3"
                style="max-width: 200px;">
              <h5 class="text-muted">Tidak ada produk yang ditemukan</h5>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@else
  <section>
    <div class="container py-5">
      <div class="d-flex justify-content-between flex-column flex-md-row gap-2">
        <h2 class="section-title mb-0">{{ $name }}</h2>
        <div class="d-flex align-items-center justify-content-between gap-4">
          <a href="{{ route("search") }}"
            class="text-decoration-none text-secondary fw-semibold d-flex align-items-center gap-2">Lihat
            semua <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
      <div class="row py-5 px-3 g-4 px-md-0">
        @if (count($products) > 0)
          @foreach ($products as $product)
            <div class="col-sm-12 col-md-4 col-lg-3">
              <x-product-item :product="$product" />
            </div>
          @endforeach
        @else
          <div>
            <div class="text-center border-0">
              <img src="/assets/img/illustrations/404-error.svg" alt="No Orders" class="img-fluid mb-3"
                style="max-width: 200px;">
              <h5 class="text-muted">Tidak ada produk yang ditemukan</h5>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endif

@push("scripts")
  @if ($type === "slider")
    <script>
      var swiper = new Swiper("#{{ $id }}_product_swiper", {
        navigation: {
          nextEl: "#{{ $id }}_product_button_next",
          prevEl: "#{{ $id }}_product_button_prev",
        },
        spaceBetween: 24,
        slidesPerView: 1,
        breakpoints: {
          // Ketika lebar layar >= 0px
          0: {
            spaceBetween: 28,
          },
          // Ketika lebar layar >= 768px
          768: {
            slidesPerView: 3,
          },
          // Ketika lebar layar >= 1024px
          1024: {
            slidesPerView: 4,
          },
        },
      });
    </script>
  @endif
@endpush
