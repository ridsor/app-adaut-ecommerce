@extends('layouts.user.app')

@php
    use App\Helpers\OrderHelper;
    use App\Helpers\Helper;
@endphp

@section('content')
    <div class="container-xl mt-4 p-0 px-md-2" x-data="order">
        <div class="head px-2 px-md-0">
            <nav class="nav nav-borders">
                <a class="nav-link ms-0 {{ Request::routeIs('user.order.index') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('user.order.index') ? route('user.order.index') : '#' }}">Semua</a>
                <a class="nav-link ms-0 {{ Request::routeIs('user.order.unpaid') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('user.order.unpaid') ? route('user.order.unpaid') : '#' }}">Belum Bayar</a>
                <a class="nav-link ms-0 {{ Request::routeIs('user.order.packed') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('user.order.packed') ? route('user.order.packed') : '#' }}">Dikemas</a>
                <a class="nav-link ms-0 {{ Request::routeIs('user.order.submitted') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('user.order.submitted') ? route('user.order.submitted') : '#' }}">Dikirim</a>
                <a class="nav-link ms-0 {{ Request::routeIs('user.order.completed') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('user.order.completed') ? route('user.order.completed') : '#' }}">Selesai</a>
                <a class="nav-link ms-0 {{ Request::routeIs('user.order.failed') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('user.order.failed') ? route('user.order.failed') : '#' }}">Dibatalkan</a>
            </nav>
            <form class="form-inline mt-3">
                <div class="input-group input-group-joined input-group-solid">
                    <input class="form-control pe-0 " type="search" placeholder="No. Pesanan" aria-label="Search"
                        name="search" value="{{ request()->query('search') }}" />
                    <div class="input-group-text"><i data-feather="search"></i></div>
                </div>
            </form>
        </div>
        <div class="body mt-4">
            <div class="order-list d-flex flex-column gap-2">
                @if (count($orders) > 0)
                    @foreach ($orders as $order)
                        <div class="order-item text-dark" style="cursor: pointer" role="link" tabindex="0"
                            aria-label="order item"
                            @click="window.location.href='{{ route('user.order.show', ['order_number' => $order->order_number]) }}'"
                            @keydown.enter="window.location.href='{{ route('user.order.show', ['order_number' => $order->order_number]) }}'"
                            @keydown.space.prevent="window.location.href='{{ route('user.order.show', ['order_number' => $order->order_number]) }}'">
                            <div class="card rounded-0">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-end mb-2">
                                        <span
                                            class="badge bg-{{ OrderHelper::getStatusClass($order->status) }}">{{ OrderHelper::getStatusLabel($order->status) }}</span>
                                    </div>
                                    <div class="order-item d-flex flex-column gap-2">
                                        @if (count($order->order_items) > 0)
                                            @foreach ($order->order_items as $item)
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <div class="product-image ratio ratio-1x1 overflow-hidden"
                                                            style="width: 80px">
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="" style="background-position: center"
                                                                class="h-100 object-fit-contain" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 flex-column gap-1 d-flex ">
                                                        <div class="order-title flex-grow-1">
                                                            <span
                                                                style="-webkit-line-clamp: 2;  -webkit-box-orient: vertical; display: -webkit-box; text-overflow: ellipsis; overflow: hidden">
                                                                {{ $item->product->name }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex gap-1 justify-content-between">
                                                            <div class="order-price text-primary">
                                                                {{ Helper::formatCurrency($item->product->price) }}
                                                            </div>
                                                            <div class="order-quantity">x {{ $item->quantity }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
                                                <a class="btn btn-primary" href="{{ $order->transaction->url }}">
                                                    Bayar Sekarang
                                                </a>
                                            @break

                                            @case('packed')
                                                <button class="btn btn-primary"
                                                    @click.stop="handleOrderSuccess('{{ $order->order_number }}')">
                                                    Selesai
                                                </button>
                                            @break

                                            @case('failed')
                                                <button class="btn btn-primary">
                                                    Beli Lagi
                                                </button>
                                            @break

                                            @case('completed')
                                                <a href="{{ route('user.review.product.index', ['order_number' => $order->order_number]) }}"
                                                    class="btn btn-primary">
                                                    Nilai
                                                </a>
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center">
                        <img src="/assets/img/illustrations/404-error.svg" alt="No Orders" class="img-fluid mb-3"
                            style="max-width: 200px;">
                        <h5 class="text-muted">Tidak ada pesanan yang ditemukan</h5>
                    </div>
                @endif
            </div>
            <div class="mt-4 pagination d-flex">
                {!! $orders->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>

        <div id="loading" x-ref="loading"
            style="
            position: fixed; 
            top: 0; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            background-color: rgba(0,0,0,.5); 
            z-index: 2; 
            display: none; 
            justify-content: center; 
            align-items: center;">
            <div style="width: 200px; height: 200px">
                <div id="loadingAnimation"></div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <style>
        .pagination>nav {
            flex: 1 !important;
        }

        .pagination nav>div:first-of-type {
            display: none !important
        }

        .pagination nav>div:last-of-type {
            display: flex !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            column-gap: .5rem
        }

        .pagination nav>div:last-of-type ul {
            justify-content: center !important;
            flex-wrap: wrap !important;
            row-gap: .5rem
        }

        @media (min-width: 992px) {
            .pagination nav>div:last-of-type {
                justify-content: space-between !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js'></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('loadingAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/assets/animations/loading.json'
        })
        document.addEventListener('alpine:init', () => {
            window.Alpine.data('order', () => ({
                handleOrderSuccess(order_number) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                    });
                    swalWithBootstrapButtons.fire({
                        title: "Apa anda sudah menerima pesanan?",
                        text: "Selesaikan pesanan",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak, Batal!",
                        reverseButtons: true
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            this.$refs.loading.style.display = 'flex'

                            const payload = {
                                status: 'completed',
                                items: [{
                                    "order_number": order_number
                                }]
                            };

                            try {
                                await window.axios.patch(
                                    '{{ route('api.user.order.update') }}',
                                    payload, {
                                        headers: {
                                            Authorization: `Bearer {{ Session::get('token') }}`,
                                            Accept: 'application/json'
                                        }
                                    }
                                );

                                this.$refs.loading.style.display = 'none';

                                location.reload();
                            } catch (error) {
                                this.$refs.loading.style.display = 'none';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal membatalkan pesanan',
                                    text: error.response?.data?.message ||
                                        'Terjadi kesalahan',
                                });
                            }
                        }
                    });
                }
            }))
        })
    </script>
@endpush
