<section class="bg-primary-subtle">
    <div class="container py-5" x-data="banner()" >
        <div class="swiper" id="banner_swiper" x-ref="source">
            <div class="swiper-wrapper w-100" >
                @if(count($banners) > 0)
                @foreach ($banners as $banner)
                <div class="swiper-slide"  :style="`height: ${height}px`">
                    <div class="row w-100 h-100">
                        <div class="col-md-8 order-2 order-md-1">
                            <div class="d-flex h-100 align-items-center">
                                <div class="p-md-5 pb-5 p-2">
                                    <h1 class="display-2 fw-medium mb-2">{{ $banner->title }}</h1>
                                    <p class="fs-6 text-secondary lh-lg">
                                        {{ $banner->description }}
                                    </p>
                                    @if ($banner->button_text)
                                    <a class="btn btn-outline-dark py-3 px-3 mt-4" href="{{ $banner->button_link }}">{{ $banner->button_text }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pe-none order-1 order-md-2 justify-center">
                            <div class="d-flex h-100 w-100 align-items-center p-2 p-md-0 ratio ratio-1x1">
                                <img src="{{ $banner->image }}"
                                    alt="" class="h-100 object-fit-contain" />
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="swiper-slide"  :style="`height: ${height}px`">
                    <div class="row w-100 h-100">
                        <div class="col-md-8 order-2 order-md-1">
                            <div class="d-flex h-100 align-items-center">
                                <div class="p-md-5 pb-5 p-2">
                                    <h1 class="display-2 fw-medium mb-2">TEMUKAN KEBAHAGIAAN BELANJA</h1>
                                    <p class="fs-6 text-secondary lh-lg">
                                        Produk berkualitas dengan pelayanan terbaik
                                    </p>
                                    <a class="btn btn-outline-dark py-3 px-3 mt-4" href="#daftar_produk">Mulai Belanja</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pe-none order-1 order-md-2 justify-center">
                            <div class="d-flex h-100 w-100 align-items-center p-2 p-md-0">
                                <img src="https://themewagon.github.io/FoodMart/images/product-thumb-1.png"
                                    alt="" class="w-100 object-fit-contain" />
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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
                // delay: 3000,
                disableOnInteraction: false,
            },
            // loop: true,
        });

        const banner = function() {
            return {
                height: "auto",
                init() {
                    const observer = new ResizeObserver(entries => {
                        this.height = entries[0].contentRect.height;
                    });
                    observer.observe(this.$refs.source);
                }
            }
        }
    </script>
@endpush
