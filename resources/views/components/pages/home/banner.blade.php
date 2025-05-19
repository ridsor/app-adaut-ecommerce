<section class="bg-primary-subtle">
    <div class="container py-5">
        <div class="swiper" id="banner_swiper">
            <div class="swiper-wrapper w-100 h-100">
                <div class="swiper-slide">
                    <div class="row">
                        <div class="col-md-8 order-2 order-md-1">
                            <div class="d-flex h-100 w-100 align-items-center">
                                <div class="p-5 mb-5">
                                    <h1 class="display-2 fw-medium mb-2">Lorem ipsum dolor sit amet</h1>
                                    <p class="fs-6 text-secondary lh-lg mb-4">Lorem ipsum dolor sit amet consectetur
                                        adipisicing elit.
                                        Ullam a
                                        accusamus temporibus
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pe-none order-1 order-md-2 justify-center">
                            <div class="d-flex h-100 w-100 align-items-center p-2 p-md-0">
                                <img src="https://themewagon.github.io/FoodMart/images/product-thumb-1.png"
                                    alt="" class="w-100 object-fit-cover" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="row">
                        <div class="col-md-8 order-2 order-md-1">
                            <div class="d-flex h-100 w-100 align-items-center">
                                <div class="p-5 mb-5">
                                    <h1 class="display-2 fw-medium mb-2">Lorem ipsum dolor sit amet</h1>
                                    <p class="fs-6 text-secondary lh-lg mb-4">Lorem ipsum dolor sit amet consectetur
                                        adipisicing elit.
                                        Ullam a
                                        accusamus temporibus
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pe-none order-1 order-md-2 justify-center">
                            <div class="d-flex h-100 w-100 align-items-center p-2 p-md-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="row">
                        <div class="col-md-8 order-2 order-md-1">
                            <div class="d-flex h-100 w-100 align-items-center">
                                <div class="p-5 mb-5">
                                    <h1 class="display-2 fw-medium mb-2">Lorem ipsum dolor sit amet</h1>
                                    <p class="fs-6 text-secondary lh-lg mb-4">Lorem ipsum dolor sit amet consectetur
                                        adipisicing elit.
                                        Ullam a
                                        accusamus temporibus
                                    </p>
                                    <button class="btn btn-outline-dark py-3 px-3">BELI SEKARANG</button>
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
