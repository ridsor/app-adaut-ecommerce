@props(['categories' => []])

<section>
    <div class="container py-5">
        <div class="swiper" id="category_swiper">
            <div class="swiper-wrapper w-100 h-100 py-5">
                @foreach ($categories as $category)
                    <div class="swiper-slide">
                        <div class="category-item">
                            <div
                                class="d-flex flex-column align-items-center justify-content-center text-center p-4 rounded-3">
                                <img src="{{ $category->icon }}" style="width: 100px; aspect-ratio: 1 / 1;" />
                                <div class="lead fs-6 fw-medium">{{ $category->name }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        var swiper = new Swiper("#category_swiper", {
            spaceBetween: 12,
            breakpoints: {
                // Ketika lebar layar >= 0px
                0: {
                    slidesPerView: 2,
                },
                // Ketika lebar layar >= 768px
                768: {
                    slidesPerView: 3,
                },
                // Ketika lebar layar >= 1024px
                1024: {
                    slidesPerView: 4,
                },
                1400: {
                    slidesPerView: 5,
                }
            }
        });
    </script>
@endpush
