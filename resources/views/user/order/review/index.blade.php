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
                                        <div class="flex-grow-1 flex-column gap-1 d-flex ">
                                            <div class="order-title flex-grow-1">
                                                <span
                                                    style="-webkit-line-clamp: 2;  -webkit-box-orient: vertical; display: -webkit-box; text-overflow: ellipsis; overflow: hidden">
                                                    {{ $item->product->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2" />
                                <div class="d-flex justify-content-between gap-2 align-items-center">
                                    <div class="review-item">
                                        <div class="d-flex gap-2 mb-1">
                                            <div class="image ration ratio-1x1 overflow-hidden"
                                                style="width: 50px; border-radius: 100%">
                                                <img src="http://images.tokopedia.net/img/cache/100-square/tPxBYm/2023/1/20/0c17a989-7381-454e-92f5-488ae5fe16f4.jpg"
                                                    alt="" class="object-fit-cover w-100 h-100"
                                                    style="background-position: center">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <div class="review-name fw-semibold">
                                                    {{ $order_review->review[0]->user->username }}
                                                </div>
                                                <div class="review-date">
                                                    <span>
                                                        {{ $order_review->review[0]->created_at->translatedFormat('d M Y H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-rating mb-1" x-data="{ rating: {{ $order_review->review[0]->rating }} }">
                                            <template x-for="i in 5" :key="i">
                                                <img :src="i <= rating ? '/icons/rate.svg' : '/icons/nonrate.svg'"
                                                    alt="star" style="width: 20px; height: 20px;">
                                            </template>
                                        </div>
                                        <div class="rating-comment">
                                            <p>{{ $order_review->review[0]->comment }}</p>
                                        </div>
                                    </div>
                                </div>
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
