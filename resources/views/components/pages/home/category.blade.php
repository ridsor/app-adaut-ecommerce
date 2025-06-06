@props(['categories' => []])

<section>
    <div class="container py-5">
        <div class="swiper" id="category_swiper">
            <div class="swiper-wrapper w-100 h-100 py-5">
                @foreach ($categories as $category)
                    <div class="swiper-slide">
                        <a href="" class="text-decoration-none">
                            <div class="category-item">
                                <div
                                    class="d-flex flex-column gap-1 align-items-center justify-content-center text-center p-4 rounded-3">
                                    <div class="ratio ratio-1x1  overflow-hidden d-flex align-items-center d-flex justify-content-center"
                                        style="width: 112px">
                                        <img src="{{ asset('storage/' . $category->icon) }}"
                                            style="background-position: center" alt=""
                                            class="h-100 object-fit-contain" />

                                    </div>
                                    <div class="lead fs-6 fw-medium text-dark">{{ $category->name }}</div>
                                </div>
                            </div>
                        </a>
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
