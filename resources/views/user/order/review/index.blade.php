@extends('layouts.user.app')

@php
    use App\Helpers\OrderHelper;
    use App\Helpers\ProductHelper;
@endphp

@section('content')
    <div class="container-xl mt-4 p-0 px-md-2">
        <div class="product-list d-flex flex-column gap-2">
            @if (count($order_review->order_items) > 0)
                @foreach ($order_review->order_items as $item)
                    <div class="order-item text-dark" style="cursor: pointer" role="link" tabindex="0"
                        aria-label="order-item"
                        @click="window.location.href='{{ route('user.review.product.show', ['order_number' => $order_review->order_number, 'slug' => $item->product->slug]) }}'"
                        @keydown.enter="window.location.href='{{ route('user.review.product.show', ['order_number' => $order_review->order_number, 'slug' => $item->product->slug]) }}'"
                        @keydown.space.prevent="window.location.href='{{ route('user.review.product.show', ['order_number' => $order_review->order_number, 'slug' => $item->product->slug]) }}'">
                        <div class="card rounded-0">
                            <div class="card-header">
                                Penilaian Produk
                            </div>
                            <div class="card-body p-2">
                                <div class="order-item">
                                    <div class="d-flex gap-2">
                                        <div>
                                            <div class="product-image ratio ratio-1x1 overflow-hidden" style="width: 80px">
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt=""
                                                    style="background-position: center" class="h-100 object-fit-contain" />
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 flex-column gap-1 d-flex justify-content-center">
                                            <div class="order-title fw-bold">
                                                <span
                                                    style="-webkit-line-clamp: 2;  -webkit-box-orient: vertical; display: -webkit-box; text-overflow: ellipsis; overflow: hidden">
                                                    {{ $item->product->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (count($item->product->reviews) > 0)
                                    <hr class="my-2" />
                                    <div class="review-item">
                                        <div class="d-flex gap-2 mb-1">
                                            <div class="image ration ratio-1x1 overflow-hidden"
                                                style="width: 50px; border-radius: 100%">
                                                <img src="{{ $user->profile?->image
                                                    ? (filter_var($user->profile->image, FILTER_VALIDATE_URL)
                                                        ? $user->profile->image
                                                        : asset('storage/' . $user->profile->image))
                                                    : '/assets/img/user-placeholder.svg' }}"
                                                    alt="" class="object-fit-cover w-100 h-100"
                                                    style="background-position: center">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <div class="review-name fw-semibold">
                                                    {{ $user->username }}
                                                </div>
                                                <div class="review-date">
                                                    <span>
                                                        {{ $item->product->reviews[0]->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-rating mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <img src="{{ $i <= $item->product->reviews[0]->rating ? '/icons/rate.svg' : '/icons/nonrate.svg' }}"
                                                    alt="star" style="width: 20px; height: 20px;">
                                            @endfor
                                        </div>
                                        <div class="rating-comment mb-1">
                                            <p>{{ $item->product->reviews[0]->comment }}</p>
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap box-container">
                                            @foreach ($item->product->reviews[0]->review_media as $index => $media)
                                                <div class="box">
                                                    <div class="inner">
                                                        <a @click.stop href="{{ asset('storage/' . $media->file_path) }}"
                                                            class="reviewGlightbox" data-type="image" data-effect="fade">
                                                            <div class="review-image"
                                                                href="{{ asset('storage/' . $media->file_path) }}"
                                                                style="width: 100px; height: 100px">
                                                                <img src="{{ asset('storage/' . $media->file_path) }}"
                                                                    alt=""
                                                                    style="object-position: center; object-fit: cover"
                                                                    class="w-100 h-100" />
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <div class="lead">Belum ada ulasan untuk produk ini</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <img src="/assets/img/illustrations/404-error.svg" alt="No Orders" class="img-fluid mb-3"
                        style="max-width: 200px;">
                    <h5 class="text-muted">Tidak ada produk yang ditemukan untuk dinilai</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('head')
    <link rel="stylesheet" href="/assets/css/glightbox.min.css" />
@endpush

@push('scripts')
    <script src="/assets/js/glightbox.min.js"></script>
    <script>
        const lightbox = GLightbox();

        const lightboxReview = GLightbox({
            selector: '.reviewGlightbox'
        });
    </script>
@endpush
