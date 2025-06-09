@extends('layouts.app')

@php
    use App\Helpers\Helper;
    (bool) ($no_stock = 0 >= $product->stock);
@endphp

@section('content')
    <main x-data="productdetail">
        <div class="container py-5">
            <div class="d-flex row mb-5">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="position-relative">
                        <div class="{{ $product->stock > 0 ? 'd-none' : '' }}">
                            <div
                                class="isEmpity position-absolute top-0 start-0 end-0 bottom-0 z-2 d-flex justify-content-center align-items-center">
                                <div class="rounded-circle p-5 d-flex justify-content-center align-items-center"
                                    style="background-color: rgba(0,0,0,.5); aspect-ratio: 1/1">
                                    <span class="text-white">Habis</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="product-image mb-3 image d-flex justify-content-center ratio ratio-1x1 align-items-center bg-card rounded-3 overflow-hidden">
                            <img src="{{ asset('storage/' . $product->image) }}" style="background-position: center"
                                alt="{{ $product->name }}" class="h-100 object-fit-contain" />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-8">
                    <h2 class="product-name h2 fw-semibold mb-2">{{ $product->name }}</h2>
                    <div class="d-flex gap-4 mb-2">
                        <div>
                            <span>Terjual <span
                                    x-text="$store.globalState.formatNumberShort({{ $product->total_sold ?? 0 }})">0</span></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="/icons/rate.svg" alt=""
                                style="width: 20px; height: 20px; transform: translateY(-2px)">
                            <span class="fw-bold"> {{ round($product->reviews_avg_rating, 1) ?? 0 }}</span>
                        </div>
                        <div>
                            <span><span
                                    x-text="$store.globalState.formatNumberShort({{ $product->review_count ?? 0 }})">0</span>
                                Penilaian</span>
                        </div>
                    </div>
                    <div class="product-price fw-bold fs-3" style="margin-bottom: 2rem">
                        {{ Helper::formatCurrency($product->price) }}</div>
                    <div class="d-flex gap-3 align-items-center justify-content-start mb-4 flex-md-row flex-column">
                        <div>
                            <span class="text-secondary">Kuantitas</span>
                        </div>
                        <div class="cart-input d-flex border rounded-2 overflow-hidden" style="width:fit-content">
                            <button class="p-0 minus border-0" :disabled="(quantity < 2) || @json($no_stock)"
                                @click="update(Number(quantity - 1))">
                                <div class="d-flex justify-content-center align-items-center"
                                    style="width: 30px; height: 30px">
                                    <i class="fa-solid fa-minus"></i>
                                </div>
                            </button>
                            <input type="number" class="rounded-1 border-0 px-2 text-center"
                                style="width: 50px; font-size: 14px; outline:none" :value="quantity"
                                :disabled="@json($no_stock)" @change="update(Number($event.target.value))" />
                            <button class="p-0 plus border-0" @click="update(Number(quantity + 1))"
                                :disabled="@json($no_stock)">
                                <div class="d-flex justify-content-center align-items-center"
                                    style="width: 30px; height: 30px">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                            </button>
                        </div>
                        <div>
                            <span class="text-secondary">tersisa <b class="text-dark">{{ $product->stock }}</b></span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-column flex-md-row">
                        <button class="btn btn-primary" :disabled="showAnimationAddCart || @json($no_stock)"
                            @click="handleCart">Keranjang</button>
                        <button class="btn
                            btn-outline-primary"
                            :disabled="@json($no_stock)" @click="handleBuyNow">Beli Sekarang</button>
                    </div>
                </div>
            </div>
            <div>
                <div class="product-description mb-5">
                    <div class="fw-semibold mb-3 h4">Deskripsi</div>
                    <div>
                        {!! Str::markdown($product->description) !!}
                    </div>
                </div>
                <div class="mb-3 card rounded-0">
                    <div class="card-body shadow-md p-0">
                        <div class="fs-6 p-2">Penilaian Produk</div>
                        <div class="shadow-sm bg-primary-subtle mx-0 p-3 mb-3 row gap-3 align-items-center">
                            <div
                                class="text-primary text-semibold fs-4 mb-1 d-flex align-items-center flex-column col-12 col-lg-auto">
                                <div>{{ round($product->reviews_avg_rating, 1) ?? 0 }} / 5</div>
                                <div class="review-rating text-nowrap">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <img src="/icons/rate.svg" alt="star" style="width: 20px; height: 20px;">
                                    @endfor
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-outline-dark border-secondary fs-responsive"
                                        @click="review.setFilter({rating: ''})"
                                        :disabled="review.meta.filter.rating === ''">
                                        Semua
                                    </button>
                                    <button class="btn btn-outline-dark border-secondary fs-responsive"
                                        @click="review.setFilter({rating: '5'})"
                                        :disabled="review.meta.filter.rating === '5'">
                                        5 Bintang
                                    </button>
                                    <button class="btn btn-outline-dark border-secondary fs-responsive"
                                        :disabled="review.meta.filter.rating === '4'"
                                        @click="review.setFilter({rating: '4'})">
                                        4 Bintang
                                    </button>
                                    <button class="btn btn-outline-dark border-secondary fs-responsive"
                                        :disabled="review.meta.filter.rating === '3'"
                                        @click="review.setFilter({rating: '3'})">
                                        3 Bintang
                                    </button>
                                    <button class="btn btn-outline-dark border-secondary fs-responsive"
                                        :disabled="review.meta.filter.rating === '2'"
                                        @click="review.setFilter({rating: '2'})">
                                        2 Bintang
                                    </button>
                                    <button class="btn btn-outline-dark border-secondary fs-responsive"
                                        :disabled="review.meta.filter.rating === '1'"
                                        @click="review.setFilter({rating: '1'})">
                                        1 Bintang
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden p-2 mb-3" x-init="review.fetchData()" id="review">
                            <!-- Loading Overlay -->
                            <div x-show="review.loading" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" class="loading-overlay">
                                <div class="loading-content d-flex justify-content-center align-items-center"
                                    style="height: 500px">
                                    <div style="width: 200px; height: 200px">
                                        <div id="review_loading"></div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="!review.loading" class="content">
                                <div class="d-flex flex-column gap-3">
                                    <template x-if="review.data.length > 0">
                                        <template x-for="item in review.data" :key="item.id">
                                            <div class="review-item d-flex flex-column gap-2">
                                                <div class="d-flex gap-2 mb-1">
                                                    <div>
                                                        <div class="image ration ratio-1x1 overflow-hidden"
                                                            style="width: 50px; border-radius: 100%">
                                                            <img :src="item.user.profile?.image ?
                                                                (isValidUrl(item.user.profile.image) ?
                                                                    item.user.profile.image :
                                                                    ('/storage/' + item.user.profile.image)) :
                                                                '/assets/img/user-placeholder.svg'"
                                                                alt="" class="object-fit-cover w-100 h-100"
                                                                style="background-position: center">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div class="review-name fw-semibold">
                                                            <span x-text="item.user.username"></span>
                                                        </div>
                                                        <div class="review-date">
                                                            <span x-text="dayjs(item.created_at).fromNow()">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-rating">
                                                    <template x-for="i in 5">
                                                        <img :src="i <= item.rating ? '/icons/rate.svg' : '/icons/nonrate.svg'"
                                                            alt="star" style="width: 20px; height: 20px;">
                                                    </template>
                                                </div>
                                                <div class="rating-comment">
                                                    <span x-text="item.comment"></span>
                                                </div>
                                                <div class="d-flex gap-2 flex-wrap box-container" x-init="initLightbox()"
                                                    x-data="lightbox">
                                                    <template x-for="media in item.review_media" :key="media.file_path">
                                                        <div class="box">
                                                            <div class="inner">
                                                                <a :href="'/storage/' + media.file_path"
                                                                    class="reviewGlightbox" data-type="image"
                                                                    data-effect="fade" @click.stop>
                                                                    <div style="width: 100px; height: 100px;">
                                                                        <img :src="'/storage/' + media.file_path"
                                                                            alt=""
                                                                            style="object-position: center; object-fit: cover"
                                                                            class="w-100 h-100" />
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="!(review.data.length > 0)">
                                        <div class="py-5">
                                            <div class="lead">Belum ada ulasan untuk produk ini</div>
                                            <p>Beli produk ini dan jadilah yang pertama memberikan ulasan</p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="pagination"
                                x-show="(review.data.length > 0) & (review.meta.total > review.meta.per_page)">
                                <!-- Previous Page Button -->
                                <button @click="review.prevPage()"
                                    :disabled="review.meta.current_page === 1 || review.loading" class="pagination-button"
                                    title="Previous Page">
                                    <i class="fas fa-angle-left"></i>
                                </button>

                                <!-- Page Number Buttons -->
                                <template x-for="page in review.visiblePages" :key="page">
                                    <button @click="review.goToPage(page)"
                                        :class="{
                                            'active': page === review.meta.current_page,
                                            'ellipsis': page === '...'
                                        }"
                                        :disabled="page === '...' || review.loading" class="pagination-button"
                                        x-text="page"></button>
                                </template>

                                <!-- Next Page Button -->
                                <button @click="review.nextPage()"
                                    :disabled="review.meta.current_page === review.meta.last_page || review.loading"
                                    class="pagination-button" title="Next Page">
                                    <i class="fas fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="showAnimationAddCart">
                <div
                    class="position-fixed top-0 bg-light bottom-0 end-0 start-0 z-2 d-flex
                    justify-content-center align-items-center pe-none">
                    <div style="width: 200px; height: 200px">
                        <div id="addcart_animation"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('head')
    <link rel="stylesheet" href="/assets/css/glightbox.min.css" />
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination-button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: #fff;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s;
            min-width: 40px;
            text-align: center;
        }

        .pagination-button:hover:not(:disabled) {
            background-color: #f0f0f0;
        }

        .pagination-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-button.active {
            background-color: #0061f2;
            color: white;
            border-color: #0061f2;
        }

        .pagination-info {
            margin: 0 15px;
            font-size: 14px;
            color: #666;
        }

        .page-input {
            width: 50px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .go-button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .pagination {
                /* flex-direction: column; */
                gap: 10px;
            }

            .pagination-group {
                display: flex;
                gap: 5px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/js/glightbox.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js'></script>
    <script>
        const animation = bodymovin.loadAnimation({
            container: document.getElementById('review_loading'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/assets/animations/loading.json'
        })

        function lightbox() {
            return {
                glightbox: null,
                initLightbox() {
                    this.$nextTick(() => {
                        if (this.glightbox) this.glightbox.destroy(); // Bersihkan instance lama
                        this.glightbox = GLightbox({
                            selector: '.reviewGlightbox'
                        });
                    });
                }
            };
        }

        document.addEventListener('alpine:init', () => {
            const isValidUrl = url => !!url && !url.startsWith('data:') && (() => {
                try {
                    return !!new URL(url)
                } catch {
                    return false
                }
            })();

            window.Alpine.data('productdetail', () => ({
                isValidUrl,
                no_stock: @json($no_stock),
                quantity: 1,
                update(value) {
                    if (value > 0 && value) {
                        this.quantity = Number(value)
                    } else {
                        this.quantity = 1
                    }
                },
                showAnimationAddCart: false,
                handleBuyNow() {
                    if (@json(!Auth::check())) {
                        window.location.href = '{{ route('login') }}'
                        return
                    }

                    Alpine.store('cart').selected = []
                    const id = window.Alpine.store('cart').add({
                        quantity: this.quantity,
                        name: @json($product->name),
                        product_id: @json($product->id),
                        price: @json($product->price),
                        weight: @json($product->weight),
                        image: @json(asset('storage/' . $product->image)),
                    })
                    Alpine.store('cart').selectOne({
                        id,
                        quantity: this.quantity,
                        name: @json($product->name),
                        product_id: @json($product->id),
                        price: @json($product->price),
                        weight: @json($product->weight),
                        image: @json(asset('storage/' . $product->image)),
                    })

                    window.location.href = '{{ route('checkout') }}'
                },
                handleCart() {
                    if (@json(!Auth::check())) {
                        window.location.href = '{{ route('login') }}'
                        return
                    }

                    window.Alpine.store('cart').add({
                        quantity: this.quantity,
                        name: @json($product->name),
                        product_id: @json($product->id),
                        price: @json($product->price),
                        weight: @json($product->weight),
                        image: @json(asset('storage/' . $product->image)),
                    })

                    this.showAnimationAddCart = true;
                    let animation = bodymovin.loadAnimation({
                        container: document.getElementById('addcart_animation'),
                        renderer: 'svg',
                        loop: false, // Animasi hanya diputar sekali
                        autoplay: true,
                        path: '/assets/animations/cart.json'
                    });

                    animation.addEventListener('complete', () => {
                        setTimeout(() => {
                            this.showAnimationAddCart =
                                false;
                            animation.destroy();
                        }, 200);
                    });
                },

                review: {
                    data: [],
                    meta: {
                        current_page: 1,
                        last_page: 1,
                        per_page: 2,
                        total: 0,
                        filter: {
                            rating: ''
                        }
                    },
                    loading: false,
                    jumpToPage: 1,

                    get visiblePages() {
                        const current = this.meta.current_page;
                        const last = this.meta.last_page;
                        const delta = 2; // Number of pages to show around current page
                        const range = [];

                        // Always show first page
                        range.push(1);

                        // Show pages around current page
                        for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current +
                                delta); i++) {
                            range.push(i);
                        }

                        // Always show last page
                        if (last > 1) {
                            range.push(last);
                        }

                        // Add ellipsis where gaps exist
                        const pagesWithDots = [];
                        let prev = null;

                        for (const page of range) {
                            if (prev !== null && page - prev > 1) {
                                pagesWithDots.push('...');
                            }
                            pagesWithDots.push(page);
                            prev = page;
                        }

                        return pagesWithDots;
                    },

                    async fetchData() {
                        this.loading = true;
                        try {
                            const response = await fetch(
                                `{{ route('api.product.review', ['product' => $product->slug]) }}?page=${this.meta.current_page}&per_page=${this.meta.per_page}&rating=${this.meta.filter.rating}`
                            );
                            const result = await response.json();

                            this.data = result.data;
                            this.meta = {
                                ...this.meta,
                                current_page: result.meta.current_page,
                                last_page: result.meta.last_page,
                                per_page: result.meta.per_page,
                                total: result.meta.total,
                            };
                            this.jumpToPage = result.meta.current_page;

                            setTimeout(() => {
                                this.scrollToTop();
                            }, 50);
                        } catch (error) {
                            console.error('Error fetching data:', error);
                        } finally {
                            this.loading = false;
                        }
                    },

                    setFilter(filter) {
                        this.meta.filter = {
                            ...this.meta.filter,
                            ...filter,
                        }
                        this.fetchData();
                    },

                    goToFirstPage() {
                        if (this.meta.current_page > 1 && !this.loading) {
                            this.meta.current_page = 1;
                            this.fetchData();
                        }
                    },

                    prevPage() {
                        if (this.meta.current_page > 1 && !this.loading) {
                            this.meta.current_page--;
                            this.fetchData();
                        }
                    },

                    nextPage() {
                        if (this.meta.current_page < this.meta.last_page && !this.loading) {
                            this.meta.current_page++;
                            this.fetchData();
                        }
                    },

                    goToLastPage() {
                        if (this.meta.current_page < this.meta.last_page && !this.loading) {
                            this.meta.current_page = this.meta.last_page;
                            this.fetchData();
                        }
                    },

                    goToPage(page) {
                        if (page === '...') return;

                        page = parseInt(page);
                        if (page >= 1 && page <= this.meta.last_page && page !== this.meta
                            .current_page && !this.loading) {
                            this.meta.current_page = page;
                            this.fetchData();
                        }
                    },
                    scrollToTop() {
                        // Scroll to the table header
                        const header = document.querySelector('#review');
                        if (header) {
                            const elementPosition = header.getBoundingClientRect().top + window
                                .pageYOffset;
                            const offsetPosition = elementPosition - 100;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }

                        // Or alternatively, scroll to top of window
                        // window.scrollTo({ top: 0, behavior: 'smooth' });
                    },

                }
            }))
        })
    </script>
@endpush
