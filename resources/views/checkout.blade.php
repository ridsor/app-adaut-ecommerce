<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Ryan Syukur" />
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="/assets/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
</head>

<body>
    <main x-data="checkout">
        <header class="page-header page-header-compact page-header-light border-bottom bg-white">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title d-flex gap-2">
                                <a class="btn btn-transparent-dark btn-icon" href="{{ route('home') }}"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-left">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg></a>
                                Checkout
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl py-4">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i data-feather="map-pin">
                        </i>
                        <span>
                            Alamat Pengiriman
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="shpiing-address">
                        @if ($user->address)
                            <a class="text-dark text-decoration-none" href="{{ route('user.account.address.index') }}">
                                <div class="content">
                                    <div class="d-flex gap-2 mb-1 fs-responsive">
                                        <span>{{ $user->address->name }}</span>
                                        <span> - </span>
                                        <span>{{ $user->address->phone_number }}</span>
                                    </div>
                                    <p class="fs-responsive mb-0">
                                        {{ $user->address->address }}
                                    </p>
                                    <p class="fs-responsive m-0">
                                        {{ $user->address->address_label }}
                                    </p>
                                </div>
                            </a>
                        @else
                            <div class="empty">
                                <p class="fs-responsive">Anda belum belum memiliki alamat pengiriman, mohon tambahkan
                                    alamat baru.
                                </p>
                                <a href="{{ route('user.account.address.index') }}" class="btn btn-primary">
                                    <span class="me-3">Tmabahkan Alamat</span>
                                    <i data-feather="plus"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <span>
                            Produk dipesan
                        </span>
                    </div>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="order-items">
                        <template x-if="!$store.cart.selected.length">
                            <div class="empty">
                                <p class="fs-responsive">Keranjang Anda kosong, silakan tambahkan produk ke keranjang
                                    terlebih dahulu.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <span class="me-3">Kembali ke Beranda</span>
                                    <i data-feather="home"></i>
                                </a>
                            </div>
                        </template>
                        <template x-if="$store.cart.selected.length">
                            <div class="d-flex flex-column gap-2">
                                <template x-for="(item, index) in $store.cart.selected" :key="index">

                                    <div class="order-item text-dark">
                                        <div class="d-flex gap-2">
                                            <div>
                                                <div class="product-image ratio ratio-1x1 overflow-hidden"
                                                    style="width: 80px">
                                                    <img :src="item.image" style="background-position: center"
                                                        alt="" class="h-100 object-fit-contain" />
                                                </div>
                                            </div>
                                            <div class="order-body flex-grow-1 flex-column gap-1 d-flex ">
                                                <div class="order-title flex-grow-1">
                                                    <span x-text="item.name"
                                                        style="-webkit-line-clamp: 2;  -webkit-box-orient: vertical; display: -webkit-box; text-overflow: ellipsis; overflow: hidden">

                                                    </span>
                                                </div>
                                                <div class="d-flex gap-1 justify-content-between">
                                                    <div class="order-price fw-semibold"
                                                        x-text="$store.globalState.formatPrice(item.price)">
                                                        Rp 0
                                                    </div>
                                                    <div class="order-quantity">x<span x-text="item.quantity">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="card-footer p-2 p-md-3">
                    <input type="text" class="form-control" placeholder="Catatan untuk penjual (opsional)"
                        x-model="data.note" />
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <span>
                            Opsi Pengiriman
                        </span>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#shippingModal">
                            Liat Semua
                        </button>
                    </div>
                </div>
                <div class="card-body ">
                    <template x-if="shipping.selected">
                        <div class="selected-shipping d-flex gap-2 align-items-center">
                            <div class="shipping-details">
                                <div class="shipping-name" x-text="shipping.selected.name"></div>
                                <div class="shipping-description fs-12" x-text="shipping.selected.description"></div>
                                <div class="shipping-price fw-semibold text-dark"
                                    x-text="$store.globalState.formatPrice(shipping.selected.cost)"></div>

                            </div>
                        </div>
                    </template>
                    <template x-if="!shipping.selected">
                        <div class="selected-shipping d-flex gap-2 align-items-center">
                            <span class="text-muted  fs-responsive">Metode pengiriman belum dipilih.</span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <span>
                            Ringasan Pembayaran
                        </span>
                    </div>
                </div>
                <div class="card-body overflow-auto">
                    <div class="order-summary mt-3 d-flex flex-column gap-2">
                        <div class="d-flex gap-1 justify-content-between flex-wrap">
                            <span class="fs-responsive">Subtotal pesanan :</span>
                            <span class="fw-semibold text-dark">
                                <span x-text="$store.globalState.formatPrice($store.cart.total($store.cart.selected))">
                                    Rp 0
                                </span>
                            </span>
                        </div>
                        <div class="d-flex gap-1 justify-content-between flex-wrap">
                            <span class="fs-responsive">Subtotal pengiriman :</span>
                            <span class="fw-semibold text-dark"
                                x-text="$store.globalState.formatPrice(shipping.selected?.cost ?? 0)">
                                Rp 0
                            </span>
                        </div>
                        <div class="d-flex gap-1 justify-content-between flex-wrap">
                            <span class="fs-responsive">Total pembayaran :</span>
                            <span class="fw-semibold text-primary"
                                x-text="$store.globalState.formatPrice($store.cart.total($store.cart.selected) + (shipping.selected?.cost ?? 0))">
                                Rp 0
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary"
                            :disabled="Boolean(!shipping.selected | isSubmit | @json(!$user->address))"
                            @click="handlePayment">
                            <span class="me-3">Buat Pesanan</span>
                            <i data-feather="arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="shippingModal" tabindex="-1" aria-labelledby="shippingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shippingModalLabel">Opsi Pengiriman</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0 overflow-hidden">
                        <div class="shipping-options">
                            <template x-if="!shipping.methods.length">
                                <div class="empty p-2">
                                    <p class="fs-responsive">Belum ada metode pengiriman yang tersedia.</p>
                                </div>
                            </template>
                            <template x-if="shipping.methods.length">
                                <div class="d-flex flex-column">
                                    <template x-for="(method, index) in shipping.methods" :key="index">
                                        <div class="shipping-option d-flex justify-content-between align-items-center border p-2"
                                            @click="shipping.select(method)" data-bs-dismiss="modal"
                                            style="cursor: pointer" role="link" tabindex="0"
                                            aria-label="shipping-option" @keydown.enter="shipping.select(method)"
                                            @keydown.space.prevent="shipping.select(method)">
                                            <div class="d-flex gap-2">
                                                <div class="shipping-details">
                                                    <div class="shipping-name fw-medium" x-text="method.name"></div>
                                                    <div class="shipping-description fs-12"
                                                        x-text="method.description"></div>
                                                    <div class="shipping-price fw-semibold text-dark"
                                                        x-text="$store.globalState.formatPrice(method.cost)"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="preloader"
        style="
    pointer-events: none; 
    position: fixed; 
    top: 0; 
    bottom: 0; 
    left: 0; 
    right: 0; 
    background-color: #f8f9fa; 
    z-index: 2; 
    display: flex; 
    justify-content: center; 
    align-items: center;">
        <div style="width: 200px; height: 200px">
            <x-loading />
        </div>
    </div>
    <script>
        const preloader = document.getElementById("preloader")
        if (preloader) {
            window.addEventListener('load', () => {
                preloader.remove()
            })
        }
    </script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (@json(Session::has('success'))) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{ Session::get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        }
        if (@json(Session::has('error'))) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "{{ Session::get('error') }}",
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
    @stack('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            window.Alpine.data('checkout', () => ({
                isSubmit: false,
                init() {
                    this.shipping.loadMethods();
                },
                data: {
                    note: undefined,
                },
                handlePayment: function() {
                    if (!@json($user->address)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Silakan lengkapi alamat pengiriman terlebih dahulu!',
                        });
                        return;
                    }
                    if (!Alpine.store('cart').selected.length) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Silahkan tambahkan produk ke keranjang terlebih dahulu!',
                        });
                        return;
                    }
                    if (!this.shipping.selected) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Silakan pilih metode pengiriman terlebih dahulu!',
                        });
                        return;
                    }
                    const orderData = {
                        note: this.data.note ?? null,
                        shipping: this.shipping.selected,
                        items: Alpine.store('cart').selected.map(item => ({
                            product_id: item.product_id,
                            quantity: item.quantity
                        }))
                    };
                    this.isSubmit = true
                    axios.post('{{ route('product.checkout.store') }}', orderData, {
                            headers: {
                                Authorization: `Bearer {{ Session::get('token') }}`,
                                Accept: 'application/json'
                            }
                        })
                        .then(response => {
                            window.location.href = response.data.data;
                            this.shipping.selected = null;
                            this.note = undefined;
                            Alpine.store('cart').remove(Alpine.store('cart').selected);
                            Alpine.store('cart').selected = [];
                        })
                        .catch(error => {
                            this.isSubmit = false;
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal membuat pesanan',
                                text: error.response.data.message,
                            });
                        });
                },
                shipping: {
                    methods: [],
                    selected: null,
                    loadMethods() {
                        axios.post('{{ route('address.domestic-cost') }}', {
                                origin: '{{ $origin }}',
                                destination: '{{ $user->address?->destination_id }}',
                                weight: Alpine.store('cart').selected.reduce((total, item) =>
                                    total + (
                                        item.weight * item.quantity), 0),
                            }, {
                                headers: {
                                    Authorization: `Bearer {{ Session::get('token') }}`,
                                    Accept: 'application/json'
                                }
                            })
                            .then(response => {
                                this.methods = response.data.data;
                            })
                            .catch(error => {
                                this.methods = [];
                                console.error('Error loading shipping methods:', error);
                            });
                    },
                    select(method) {
                        this.selected = method;
                    }
                },
                selectShippingMethod(method) {
                    this.shipping.select(method);
                    $('#shippingModal').modal('hide');
                }
            }));
        });
    </script>
</body>

</html>
