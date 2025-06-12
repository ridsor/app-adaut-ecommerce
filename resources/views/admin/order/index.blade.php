@extends('layouts.admin.app')

@php
    use App\Helpers\OrderHelper;
    use App\Helpers\Helper;
@endphp

@section('content')
    <main x-data="data">
        <div class="page-header page-header-dark bg-gradient-primary-to-secondary mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title fs-1">
                                <div class="page-header-icon"><i data-feather="file"></i></div>
                                {{ $orders->total() }} Total Pesanan
                            </h1>
                            <div class="page-header-subtitle">Lihat dan perbarui daftar pesanan Anda di sini.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-xl list">
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex mt-5 align-items-center justify-content-between gap-2">
                <h4 class="mb-0">Pesanan</h4>
            </div>
            <hr class="mt-2 mb-3">
            <div class="mb-3">
                <form action="{{ route('admin.order.index') }}" id="orderForm" action="">
                    <div class="order-header row g-2 g-xl-4 mb-4 flex-wrap">
                        <div class="col-12 col-lg-6">
                            <div class="input-group input-group-joined input-group-solid">
                                <input class="form-control pe-0 " type="search" placeholder="No. Pesanan"
                                    aria-label="Search" name="search" value="{{ request()->query('search') }}" />
                                <div class="input-group-text"><i data-feather="search"></i></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 ms-auto">
                            <select class="form-select py-3" name="sort" aria-label="sort" @change="sorftBy($event)">
                                <option selected value="">Urutkan</option>
                                @foreach ($sort as $x)
                                    @if ($x['value'] === request()->input('sort'))
                                        <option value="{{ $x['value'] }}" selected>{{ $x['text'] }}</option>
                                    @else
                                        <option value="{{ $x['value'] }}">{{ $x['text'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-3 d-grid">
                            <!-- Filter offcanvas button -->
                            <button class="btn btn-primary mb-0" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                                <i class="fas fa-sliders-h me-1"></i> Filter
                            </button>
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSidebar"
                                aria-labelledby="offcanvasSidebarLabel">
                                <div class="offcanvas-header bg-light">
                                    <h6 class="offcanvas-title" id="offcanvasSidebarLabel">Filter</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        data-bs-target="#offcanvasSidebar" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body flex-column p-3 p-xl-0">
                                    <div class="accordion accordion-alt" id="accordionSearch">
                                        <!-- Status -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fs-6 fw-semibold" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                    aria-expanded="true" aria-controls="collapseOne">
                                                    Status
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                data-bs-parent="#accordionSearch" style="">
                                                <div class="accordion-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input focus-shadow-none" type="radio"
                                                            checked name="status" id="all" value="">
                                                        <label class="form-check-label" for="status">
                                                            Semua
                                                        </label>
                                                    </div>
                                                    @foreach ($status as $item)
                                                        <div class="form-check">
                                                            <input class="form-check-input focus-shadow-none" type="radio"
                                                                name="status" id="{{ $item->status }}"
                                                                {{ request()->input('status') === $item->status ? 'checked' : '' }}
                                                                value="{{ $item->status }}">
                                                            <label class="form-check-label" for="status">
                                                                {{ OrderHelper::getStatusLabel($item->status) }}
                                                                ({{ $item->total }})
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Courir -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fs-6 fw-semibold collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Kurir
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionSearch" style="">
                                                <div class="accordion-body">
                                                    @foreach ($courir as $index => $item)
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="courir[]"
                                                                {{ in_array($item->name, request()->input('courir', [])) ? 'checked' : '' }}
                                                                type="checkbox" value="{{ $item->name }}"
                                                                id='item{{ $index }}' />
                                                            <label class="form-check-label heading-color"
                                                                for='item{{ $index }}'>{{ $item->name }}</label>
                                                            <span class="small float-end">({{ $item->total }})</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between p-2 mt-xl-3 align-items-center">
                                        <a class="btn btn-link text-primary-hover p-0 mb-0 text-decoration-none"
                                            href="{{ route('admin.order.index') }}?{{ http_build_query(['value' => request()->query('value')]) }}">Hapus
                                            Semua</a>
                                        <button class="btn btn-primary mb-0" type="submit">Terapkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive" style="min-height: 300px">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Pesanan</th>
                                    <th class="text-nowrap">Pembeli</th>
                                    <th class="text-nowrap">Produk</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Metode Pengiriman</th>
                                    <th class="text-nowrap">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($orders) > 0)
                                    @foreach ($orders as $order)
                                        <tr x-data="{ active: false }" class="position-relative order-item"
                                            style="cursor: pointer" role="link" tabindex="0"
                                            aria-label="order_item" @focus="active = true" @blur="active = false"
                                            @mouseover="active = true" @mouseleave="active = false">

                                            <td>{{ $order->order_number }}</td>
                                            <td><span class="text-nowrap">{{ $order->user->name }}</span></td>
                                            <td>
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="text-nowrap fw-semibold">{{ $order->order_items_count }}
                                                        Produk</span>
                                                    <div class="me-3">
                                                        <div class="product-image ratio ratio-1x1 overflow-hidden"
                                                            style="width: 34px">
                                                            <img src="{{ asset('storage/' . $order->order_items[0]->product->image) }}"
                                                                alt="" style="background-position: center"
                                                                class="h-100 object-fit-contain" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ OrderHelper::getStatusClass($order->status) }}">
                                                    {{ OrderHelper::getStatusLabel($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->shipping->name }}</td>
                                            <td class="text-nowrap">
                                                {{ Helper::formatCurrency($order->amount, 0, ',', '.') }}</td>

                                            <td x-transition.opacity x-show="active" x-init="$el.classList.remove('d-none');"
                                                class="d-none order-action position-absolute top-0 bottom-0 end-0 start-0"
                                                style="z-index: 1; background: rgba(0,0,0,.3)">
                                                <div class="d-flex justify-content-start align-items-center h-100">
                                                    <div class="d-flex gap-1">

                                                        <a @focus="active = true" @blur="active = false"
                                                            class="btn btn-primary"
                                                            href="{{ route('admin.order.show', ['pesanan' => $order->order_number]) }}">Detail
                                                            Pesanan</a>
                                                        @if ($order->status != 'failed' && $order->status != 'completed' && $order->status != 'unpaid')
                                                            <div class="dropdown" x-data="{ dropdown: false }">
                                                                <button button @focus ="active=true"
                                                                    @blur="active = false"
                                                                    class="btn btn-primary dropdown-toggle" type="button"
                                                                    @click.stop="dropdown=!dropdown"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <div x-show="dropdown">
                                                                        <i data-feather="chevron-up"></i>
                                                                    </div>
                                                                    <div x-show="!dropdown">
                                                                        <i data-feather="chevron-down"></i>
                                                                    </div>
                                                                </button>
                                                                <ul class="dropdown-menu z-3">
                                                                    @if ($order->status != 'submitted')
                                                                        <li>
                                                                            <button class="dropdown-item" type="button"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#awbModal"
                                                                                @click.stop="active = true; handleOrderDelivery('{{ $order->order_number }}','{{ $order->shipping->awb }}')"
                                                                                @focus="active = true"
                                                                                @blur="active = false">Atur
                                                                                Pengiriman</button>
                                                                        </li>
                                                                    @endif
                                                                    @if ($order->status != 'packed')
                                                                        <li>
                                                                            <button class="dropdown-item"
                                                                                @click.stop="active = true; handleOrderPacked('{{ $order->order_number }}')"
                                                                                @focus="active = true"
                                                                                @blur="active = false">Atur
                                                                                Kemasan</button>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <button @focus="active = true"
                                                                            @blur="active = false"
                                                                            @click.stop="active = true; handleOrderCanceled('{{ $order->order_number }}')"
                                                                            class="dropdown-item" href="#">Batalkan
                                                                            Pesanan</button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center border-0">
                                            <img src="/assets/img/illustrations/404-error.svg" alt="No Orders"
                                                class="img-fluid mb-3" style="max-width: 200px;">
                                            <h5 class="text-muted">Tidak ada pesanan yang ditemukan</h5>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="mt-4 pagination d-flex">
                {!! $orders->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>

        {{-- Loading --}}
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
        <!-- Modal -->
        <form :action="awb.route" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal fade" id="awbModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
                aria-hidden="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmModalLabel">Atur Pengiriman</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="small mb-1" for="awb">Masukan nomor resi pengiriman</label>
                                <input class="form-control  @if ($errors->awb->has('awb')) is-invalid @endif"
                                    id="awb" name="awb" type="text"
                                    :value="('{{ old('awb') }}') ? '{{ old('awb') }}' : awb.value" />
                                @if ($errors->awb->has('awb'))
                                    <div class="invalid-feedback">
                                        {{ $errors->awb->get('awb')[0] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer"><button class="btn btn-danger" type="button"
                                data-bs-dismiss="modal">Batal</button><button class="btn btn-success"
                                type="submit">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

@push('head')
    <style>
        .order-item .dropdown-toggle::after {
            display: none !important;
        }

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('loadingAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/assets/animations/loading.json'
        })

        document.addEventListener('alpine:init', () => {
            window.Alpine.data('data', () => ({
                awb: {
                    value: '',
                    route: ''
                },
                async handleOrderDelivery(order_number, awb) {
                    console.log(this.awb)
                    this.awb.route = "{{ route('admin.order.index') }}/" + order_number
                    this.awb.value = awb
                },
                async handleOrderPacked(order_number) {
                    this.$refs.loading.style.display = 'flex'

                    const payload = {
                        status: 'packed',
                        items: [{
                            order_number: order_number
                        }]
                    };

                    try {
                        await axios.patch(
                            '{{ route('api.admin.order.update') }}',
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
                            title: 'Gagal memperbarui pesanan',
                            text: error.response?.data?.message ||
                                'Terjadi kesalahan',
                        });
                    }

                },
                handleOrderCanceled(order_number) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                    });
                    swalWithBootstrapButtons.fire({
                        title: "Apa anda yakin?",
                        text: "membatalkan pesanan",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak, Batal!",
                        reverseButtons: true
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            this.$refs.loading.style.display = 'flex'

                            const payload = {
                                status: 'failed',
                                items: [{
                                    "order_number": order_number
                                }]
                            };

                            try {
                                await window.axios.patch(
                                    '{{ route('api.admin.order.update') }}',
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
            }));
        });

        function sorftBy(event) {
            const form = event.target.form;
            const params = new URLSearchParams(new FormData(form));

            window.location.href = `${form.action}?${params.toString()}`;
        }

        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById("awbModal"));
            if (@json($errors->awb->isNotEmpty())) {
                myModal.show();
            }
        });
    </script>
@endpush
