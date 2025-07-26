@props(['order'])

@php
    use App\Helpers\OrderHelper;
    use App\Helpers\Helper;
@endphp

<div class="order-item text-dark" style="cursor: pointer" role="link" tabindex="0" aria-label="order item"
    @click="window.location.href='{{ route('user.order.show', ['order_number' => $order->order_number]) }}'"
    @keydown.enter="window.location.href='{{ route('user.order.show', ['order_number' => $order->order_number]) }}'"
    @keydown.space.prevent="window.location.href='{{ route('user.order.show', ['order_number' => $order->order_number]) }}'">
    <div class="order-item card rounded-0">
        <div class="card-body p-2">
            <div class="d-flex justify-content-end mb-2">
                <span
                    class="badge bg-{{ OrderHelper::getStatusClass($order->status) }}">{{ OrderHelper::getStatusLabel($order->status) }}</span>
            </div>
            <div class="order-item d-flex flex-column gap-2">
                @if (count($order->order_items) > 0)
                    <div class="d-flex gap-2">
                        <div>
                            <div class="product-image ratio ratio-1x1 overflow-hidden" style="width: 80px">
                                <img src="{{ asset('storage/' . $order->order_items[0]->product?->image) }}"
                                    alt="" style="background-position: center"
                                    class="h-100 object-fit-contain" />
                            </div>
                        </div>
                        <div class="flex-grow-1 flex-column gap-1 d-flex ">
                            <div class="order-title flex-grow-1">
                                <span
                                    style="-webkit-line-clamp: 2;  -webkit-box-orient: vertical; display: -webkit-box; text-overflow: ellipsis; overflow: hidden">
                                    {{ $order->order_items[0]->product?->name }}
                                </span>
                            </div>
                            <div class="d-flex gap-1 justify-content-between">
                                <div class="order-price text-primary">
                                    {{ Helper::formatCurrency($order->order_items[0]->product?->price) }}
                                </div>
                                <div class="order-quantity">x
                                    {{ $order->order_items[0]->quantity }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <hr class="my-2" />
            <div class="d-flex justify-content-between gap-2 align-items-center">
                <span class="text-muted fs-responsive">
                    {{ count($order->order_items) }} Produk
                </span>
                <span>
                    Total Pesanan: <span
                        class="text-primary">{{ Helper::formatCurrency($order->amount, 0, ',', '.') }}</span>
                </span>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-2 align-items-center flex-wrap">
                @switch($order->status)
                    @case('unpaid')
                        <a @click.stop class="btn btn-primary" href="{{ $order->transaction->url }}">
                            Bayar Sekarang
                        </a>
                    @break

                    @case('submitted')
                        <button class="btn btn-primary" @click.stop="handleOrderSuccess('{{ $order->order_number }}')">
                            Selesai
                        </button>
                    @break

                    @case('completed')
                        <a @click.stop
                            href="{{ route('user.review.product.index', ['order_number' => $order->order_number]) }}"
                            class="btn btn-primary">
                            Nilai
                        </a>
                    @break
                @endswitch
            </div>
        </div>
    </div>
</div>
