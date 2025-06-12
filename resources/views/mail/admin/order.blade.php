@php
    use App\Helpers\Helper;
    use App\Helpers\OrderHelper;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Ryan Syukur" />
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://kit.fontawesome.com/0c8762722d.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link href="/assets/css/styles.css" rel="stylesheet" />

    @vite(['resources/js/app.js'])
</head>

<body>
    <main>
        <!-- Invoice-->
        <div class="invoice">
            <div class="card-header p-4 p-md-5 border-bottom-0 bg-gradient-primary-to-secondary text-white-50">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-lg-auto mb-5 mb-lg-0 text-center text-lg-start">
                        <!-- Invoice branding-->
                        <img class="invoice-brand-img rounded-circle mb-4" src="/assets/img/logo.svg" alt="" />
                        <div class="h2 text-white mb-0">{{ env('APP_NAME') }}</div>
                    </div>
                    <div class="col-12 col-lg-auto text-center text-lg-end">
                        <!-- Invoice details-->
                        <div class="h3 text-white">Pesanan</div>
                        {{ $order->transaction->invoice }}
                        <br />
                        {{ $order->transaction->created_at->translatedFormat('d-m-Y H:i:s') }}
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="text-dark mb-4">
                    <p>Pesanan dengan nomor transaksi <strong>{{ $order->transaction->invoice }}</strong> telah dibuat
                        oleh pelanggan.
                        Silakan lakukan pengecekan dan tindak lanjut sesuai prosedur operasional.
                        <br />
                        Silahkan Klik link di bawah ini untuk melihat detail pesanan :
                    </p>


                    <div class="py-4 text-center">
                        <a href="{{ route('admin.order.show', ['pesanan' => $order->order_number]) }}"
                            class="fs-1 text-decoration-none">
                            Detail Pesanan
                        </a>
                    </div>
                </div>
                <!-- User table -->
                <div class="user mb-5">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-start pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Nama Pelanggan:</div>
                                    </td>
                                    <td class="text-start pb-0">
                                        <div class="h5 mb-0 fw-700">
                                            {{ $order->user->name }}</div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Status:</div>
                                    </td>
                                    <td class="text-start pb-0">
                                        <div class="h5 mb-0 fw-700">
                                            {{ OrderHelper::getStatusLabel($order->status) }}</div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Catatan:</div>
                                    </td>
                                    <td class="text-start pb-0">
                                        <div class="mb-0 text-muted">
                                            {{ $order->note ? $order->note : ' - ' }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shipping table -->
                <div class="shipping mb-5">
                    <div class="mb-2">
                        <span class="h2 text-dark">Informasi Pengiriman</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>

                                <tr>
                                    <td class="text-start pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Nama Penerima:</div>
                                    </td>
                                    <td class="text-start pb-0">
                                        <div class="h5 mb-0 fw-700">
                                            {{ $order->shipping->recipient_name }}</div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Alamat:</div>
                                    </td>
                                    <td class="text-start pb-0">
                                        <div class="mb-0">
                                            <p class="mb-0">
                                                {{ $order->shipping->address }}
                                            </p>
                                            <p class="m-0">
                                                {{ $order->shipping->address_label }}
                                            </p>
                                            <p class="m-0">
                                                {{ $order->shipping->note }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">No Telepon:</div>
                                    </td>
                                    <td class="text-start pb-0">
                                        <div class="h5 mb-0 fw-700">
                                            {{ $order->shipping->phone_number }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-start pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Metode Pengiriman:</div>
                                    </td>
                                    <td class="text-start pb-0">
                                        <div class="h5 mb-0 fw-700">
                                            {{ $order->shipping->name }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Invoice table-->
                <div class="order">
                    <div class="mb-2">
                        <span class="h2 text-dark">Rincian Pesanan</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="border-bottom">
                                <tr class="small text-uppercase text-muted">
                                    <th scope="col">Produk</th>
                                    <th class="text-end" scope="col">Harga</th>
                                    <th class="text-end" scope="col">Jumlah</th>
                                    <th class="text-end" scope="col">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Invoice item -->
                                @foreach ($order->order_items as $item)
                                    <tr class="border-bottom">
                                        <td>
                                            <div class="d-flex">
                                                <div class="me-3">
                                                    <div class="product-image ratio ratio-1x1 overflow-hidden"
                                                        style="width: 60px">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                            alt="" style="background-position: center"
                                                            class="h-100 object-fit-contain" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 text-nowrap fw-bold">{{ $item->product->name }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end text-nowrap fw-bold">
                                            {{ Helper::formatCurrency($item->product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end text-nowrap fw-bold">
                                            {{ Helper::formatCurrency($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Invoice subtotal-->
                                <tr>
                                    <td class="text-end pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Subtotal:</div>
                                    </td>
                                    <td class="text-end pb-0">
                                        <div class="h5 mb-0 fw-700">
                                            {{ Helper::formatCurrency($order->total_price, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                                <!-- Invoice tax column-->
                                <tr>
                                    <td class="text-end pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Subtotal pengiriman:</div>
                                    </td>
                                    <td class="text-end pb-0">
                                        <div class="h5 mb-0 fw-700">
                                            {{ Helper::formatCurrency($order->shipping->cost, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                                <!-- Invoice total-->
                                <tr>
                                    <td class="text-end pb-0" colspan="3">
                                        <div class="text-uppercase small fw-700 text-muted">Total Pembayaran:</div>
                                    </td>
                                    <td class="text-end pb-0">
                                        <div class="h5 mb-0 fw-700 text-green">
                                            {{ Helper::formatCurrency($order->amount, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer p-4 p-lg-5 border-top">
                <div class="d-flex flex-column text-center">
                    <span class="fw-bold">Adaut</span>
                    <span>{{ date('Y') }}</span>
                </div>
            </div>
        </div>
    </main>
    {{-- JS --}}
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
