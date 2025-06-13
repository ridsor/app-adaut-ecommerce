<section id="hero" class="hero section">
    <div class="container">
        <div class="swiper" id="banner_swiper">
            <div class="swiper-wrapper w-100">
                <div class="swiper-slide px-2">

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right" data-aos-delay="100">
                            <div class="d-flex flex-column gap-4">
                                <h1 class="hero-title mb-0">
                                    Belanja Produk Lokal Tanpa Batas, Kapan Saja Dimana Saja
                                </h1>

                                <p class="hero-description mb-0">
                                    Temukan berbagai produk UMKM lokal terbaik mulai dari makanan, fashion, hingga
                                    kerajinan
                                    tangan. Belanja mudah dan aman dengan teknologi terkini.
                                </p>

                                <div class="cta-wrapper">
                                    <a href="{{ route('home') }}/#daftar-produk" class="btn btn-primary">
                                        Belanja Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                            <div class="hero-image">
                                <img src="/storage/gambar/spanduk/2DrL5nKu24aLq2Twl0FhJO2ATOZRzebevTQaaha1.jpg"

                                    alt="Business Growth" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($banners as $banner)
                    <div class="swiper-slide px-3">
                        <div class="row align-items-center mb-5">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="d-flex flex-column gap-4">
                                    <h1 class="hero-title mb-0">
                                        {{ $banner->title }}
                                    </h1>

                                    <p class="hero-description mb-0">
                                        {{ $banner->description }}
                                    </p>

                                    @if ($banner->button_text)
                                        <div class="cta-wrapper">
                                            <a href="{{ $banner->button_link }}"
                                                class="btn btn-primary">{{ $banner->button_text }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Business Growth"
                                        class="img-fluid" loading="lazy" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination" id="banner_swiper_pagination"></div>
        </div>
    </div>
</section>
@push('scripts')
    <script>
        var swiper = new Swiper('#banner_swiper', {
            pagination: {
                el: "#banner_swiper_pagination",
                clickable: true,
                dynamicBullets: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            loop: true,
        });
    </script>
@endpush
