@props(['categories' => []])

<section>
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="swiper" id="category_swiper">
            <div class="swiper-wrapper w-100 h-100">
                @foreach ($categories as $index => $category)
                    <div class="swiper-slide">
                        <div class="category-card d-flex align-items-center flex-column aos-init aos-animate"
                            data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="category-image  mb-3">
                                <img src="{{ asset('storage/' . $category->icon) }}" alt="Category" class="img-fluid"
                                    style="width: 100px">
                            </div>
                            <h3 class="category-title text-primary mb-1">{{ $category->name }}</h3>
                            <p class="category-count mb-0">{{ $category->products_count }} Produk</p>
                            <a href="{{ route('search') }}?categories[]={{ $category->slug }}"
                                class="stretched-link"></a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="category_button_prev" class="position-absolute z-1 start-0 shadow-md"
                style="transform: translateY(-50%)translateX(-20%); top: 50%;">
                <div class="bg-secondary-subtle btn rounded-circle text-dark d-flex align-items-center justify-content-center"
                    style="width: 38px; height: 38px; ">
                    <i class="fa-solid fa-angle-left"></i>
                </div>
            </div>
            <div id="category_button_next" class="position-absolute z-1 end-0 shadow-md"
                style="transform: translateY(-50%)translate(20%); top: 50%;">
                <div class="bg-secondary-subtle rounded-circle btn text-dark d-flex align-items-center justify-content-center"
                    style="width: 38px; height: 38px; ">
                    <i class="fa-solid fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        var swiper = new Swiper("#category_swiper", {
            navigation: {
                nextEl: "#category_button_next",
                prevEl: "#category_button_prev",
            },
            spaceBetween: 20,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            loop: true,
            breakpoints: {
                // Ketika lebar layar >= 0px
                0: {
                    slidesPerView: 2,
                },
                // Ketika lebar layar >= 768px
                768: {
                    slidesPerView: 4,
                },
                // Ketika lebar layar >= 1024px
                1024: {
                    slidesPerView: 5,
                },
                1400: {
                    slidesPerView: 6,
                }
            }
        });
    </script>
@endpush
