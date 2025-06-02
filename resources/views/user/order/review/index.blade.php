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
                                @if(count($item->product->reviews) > 0) 
                                <hr class="my-2" />
                                <div class="d-flex justify-content-between gap-2 align-items-center">
                                    <div class="review-item">
                                        <div class="d-flex gap-2 mb-1">
                                            <div class="image ration ratio-1x1 overflow-hidden"
                                                style="width: 50px; border-radius: 100%">
                                                <img src="{{ Auth::user()->profile?->image
                                                ? (filter_var(Auth::user()->profile->image, FILTER_VALIDATE_URL)
                                                    ? Auth::user()->profile->image
                                                    : asset('storage/' . Auth::user()->profile->image))
                                                : '/assets/img/illustrations/profiles/profile-2.png' }}"
                                                    alt="" class="object-fit-cover w-100 h-100"
                                                    style="object-position: center">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <div class="review-name fw-semibold">
                                                    {{ Auth::user()->username }}
                                                </div>
                                                <div class="review-date">
                                                    <span>
                                                        {{ $item->product->reviews[0]->created_at->translatedFormat('d M Y H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-rating mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                            <img src="{{  $i <= $item->product->reviews[0]->rating ? '/icons/rate.svg' : '/icons/nonrate.svg' }}"
                                                alt="star" style="width: 20px; height: 20px;">
                                            @endfor
                                        </div>
                                        <div class="rating-comment mb-1">
                                            <p>{{ $item->product->reviews[0]->comment }}</p>
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach ($item->product->reviews[0]->review_media as $media)
                                            <div class="product-image overflow-hidden position-relative"
                                                style="width: 100px; height: 100px">
                                                <img src="{{ asset('storage/'.$media->file_path) }}" alt=""
                                                style="object-position: center; object-fit: cover" class="w-100 h-100" />
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
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
