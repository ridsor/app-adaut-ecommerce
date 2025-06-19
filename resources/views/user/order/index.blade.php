@extends('layouts.user.app')

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
                        <x-pages.user.order.order-item :order="$order" />
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
