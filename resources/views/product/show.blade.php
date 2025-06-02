@extends("layouts.app")

@php
(bool)$no_stock = 0 >= $product->stock;
@endphp

@section("content")
  <main x-data="productdetail">
    <div class="container py-5">
      <div class="d-flex row mb-5">
        <div class="col-12 col-md-6 col-lg-4">
          <div class="position-relative">
            <div class="{{ $product->stock > 0 ? "d-none" : "" }}">
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
              <img src="{{ asset("storage/" . $product->image) }}" style="background-position: center"
                alt="{{ $product->name }}" class="h-100 object-fit-contain" />
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-8">
          <h2 class="product-name h2 fw-bold">{{ $product->name }}</h2>
          <div class="d-flex gap-4 mb-2">
            <div>
              <span>Terjual <span
                  x-text="$store.globalState.formatNumberShort({{ $product->total_sold ?? 0 }})">0</span></span>
            </div>
            <div class="d-flex align-items-center">
              <img src="/icons/rate.svg" alt="" style="width: 20px; height: 20px; transform: translateY(-2px)">
              <span class="fw-bold"> {{ round($product->reviews_avg_rating, 1) ?? 0 }}</span>
            </div>
            <div>
              <span><span x-text="$store.globalState.formatNumberShort({{ $product->review_count ?? 0 }})">0</span>
                Penilaian</span>
            </div>
          </div>
          <div class="product-price fw-bold fs-3" style="margin-bottom: 2rem"
            x-text="$store.globalState.formatPrice({{ $product->price }})">Rp 0</div>
          <div class="d-flex gap-3 align-items-center justify-content-start mb-4 flex-md-row flex-column">
            <div>
              <span class="text-secondary">Kuantitas</span>
            </div>
            <div class="cart-input d-flex border rounded-2 overflow-hidden" style="width:fit-content">
              <button class="p-0 minus border-0" :disabled="(quantity < 2) || @json($no_stock)"
                @click="update(Number(quantity - 1))">
                <div class="d-flex justify-content-center align-items-center" style="width: 30px; height: 30px">
                  <i class="fa-solid fa-minus"></i>
                </div>
              </button>
              <input type="number" class="rounded-1 border-0 px-2 text-center"
                style="width: 50px; font-size: 14px; outline:none" :value="quantity"
                :disabled="@json($no_stock)" @change="update(Number($event.target.value))" />
              <button class="p-0 plus border-0" @click="update(Number(quantity + 1))"
                :disabled="@json($no_stock)">
                <div class="d-flex justify-content-center align-items-center" style="width: 30px; height: 30px">
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
                            btn-outline-primary" :disabled="@json($no_stock)"
              @click="handleBuyNow">Beli Sekarang</button>
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
        <div class="product-review" style="max-width: 800px">
          <div class="fw-semibold mb-3 h4">Ulasan</div>
          <div class="review-list d-flex flex-column gap-3">
            @if (count($product->reviews) > 0)
              @foreach ($product->reviews as $review)
                <div class="review-item">
                  <div class="d-flex gap-2 mb-1">
                    <div class="image ration ratio-1x1 overflow-hidden" style="width: 50px; border-radius: 100%">
                      <img
                        src="http://images.tokopedia.net/img/cache/100-square/tPxBYm/2023/1/20/0c17a989-7381-454e-92f5-488ae5fe16f4.jpg"
                        alt="" class="object-fit-cover w-100 h-100" style="background-position: center">
                    </div>
                    <div class="d-flex flex-column">
                      <div class="review-name fw-semibold">
                        {{ $review->user->username }}
                      </div>
                      <div class="review-date">
                        <span x-text="$store.globalState.formatTimeAgo('{{ $review->created_at }}')">
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="review-rating mb-1">
                    @for ($i = 1; $i <= 5; $i++)
                      <img src="{{ $i <= $review->rating ? "/icons/rate.svg" : "/icons/nonrate.svg" }}" alt="star"
                        style="width: 20px; height: 20px;">
                    @endfor
                  </div>
                  <div class="rating-comment mb-1">
                    <p>{{ $review->comment }}</p>
                  </div>
                  <div class="d-flex gap-2 flex-wrap box-container">
                    @foreach ($review->review_media as $index => $media)
                      <div class="box">
                        <div class="inner">
                          <a href="{{ asset("storage/" . $media->file_path) }}" class="reviewGlightbox"  data-type="image" data-effect="fade">
                            <div class="review-image" href="{{ asset("storage/" . $media->file_path) }}"
                              style="width: 100px; height: 100px">
                              <img src="{{ asset("storage/" . $media->file_path) }}" alt=""
                                style="object-position: center; object-fit: cover" class="w-100 h-100" />
                            </div>
                          </a>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            @else
              <div>
                <div class="lead">Belum ada ulasan untuk produk ini</div>
                <p>Beli produk ini dan jadilah yang pertama memberikan ulasan</p>
              </div>
            @endif
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

@push("head")
  <link rel="stylesheet" href="/assets/css/glightbox.min.css" />
@endpush

@push("scripts")
  <script src="/assets/js/glightbox.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js'></script>
  <script>
    document.addEventListener('alpine:init', () => {
      window.Alpine.data('productdetail', () => ({
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
            window.location.href = '{{ route("login") }}'
            return
          }

          Alpine.store('cart').selected = []
          const id = window.Alpine.store('cart').add({
            quantity: this.quantity,
            name: '{{ $product->name }}',
            product_id: {{ $product->id }},
            price: {{ $product->price }},
            weight: {{ $product->weight }},
            image: '{{ asset("storage/" . $product->image) }}',
          })
          Alpine.store('cart').selectOne({
            id,
            quantity: this.quantity,
            name: '{{ $product->name }}',
            product_id: {{ $product->id }},
            price: {{ $product->price }},
            weight: {{ $product->weight }},
            image: '{{ asset("storage/" . $product->image) }}',
          })

          window.location.href = '{{ route("checkout") }}'
        },
        handleCart() {
          if (@json(!Auth::check())) {
            window.location.href = '{{ route("login") }}'
            return
          }

          window.Alpine.store('cart').add({
            quantity: this.quantity,
            name: '{{ $product->name }}',
            product_id: {{ $product->id }},
            price: {{ $product->price }},
            weight: {{ $product->weight }},
            image: '{{ asset("storage/" . $product->image) }}',
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
        }
      }))
    })

    const lightbox = GLightbox();

    const lightboxReview = GLightbox({
                selector: '.reviewGlightbox'
            });
  </script>
@endpush
