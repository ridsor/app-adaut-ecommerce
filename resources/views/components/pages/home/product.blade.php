@props(['products' => [], 'type' => 'default', 'name' => 'Produk'])
@php
    $id = str_replace(' ', '_', strtolower($name));
@endphp

@if ($type === 'slider')
    <section class="product">
        <div class="container section-title" data-aos="fade-up">
            <h2>Best Sellers</h2>
            <p>Temukan produk terpopuler kami yang dicintai oleh pelanggan di seluruh dunia. Kualitas premium, nilai
                luar biasa, dan gaya yang sedang tren di satu tempat</p>
        </div><!-- End Section Title -->
        <div class="container">
            <!-- Section Title -->
            <div class="d-flex justify-content-between gap-2 mb-3">
                <div class="d-flex" style="gap: 8px" data-aos="fade-right" data-aos-delay="100">
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
                <div class="d-flex align-items-center justify-content-between gap-4" data-aos="fade-left"
                    data-aos-delay="200">
                    <a href="{{ route('search') }}?sort=bestsellers"
                        class="text-decoration-none text-secondary fw-semibold d-flex align-items-center gap-2">Lihat
                        semua <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="swiper px-3 px-md-0" id="{{ $id }}_product_swiper">
                @if (count($products) > 0)
                    <div class="swiper-wrapper w-100 h-100">
                        @foreach ($products as $index => $product)
                            <div class="swiper-slide" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
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
    <section id="daftar-produk" class="product">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Produk Terbaru</h2>
        </div><!-- End Section Title -->
        <div class="container">
            <div class="row px-3 g-4 px-md-0">
                @if (count($products) > 0)
                    @foreach ($products as $index => $product)
                        <div class="col-sm-12 col-md-4 col-lg-3" data-aos="fade-up"
                            data-aos-delay="{{ $index * 100 }}">
                            <x-product-item :product="$product" />
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-center" data-aos="fade-up">
                        <a href="{{ route('search') }}" class="read-more"><span>Lihat Semua</span><i
                                class="bi bi-arrow-right"></i></a>
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
@endif

@push('scripts')
    @if ($type === 'slider')
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
