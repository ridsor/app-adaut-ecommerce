@php
    use App\Helpers\Helper;
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
                        <div class="h3 text-white">Invoice</div>
                        {{ $order->transaction->invoice }}
                        <br />
                        {{ $order->transaction->created_at->translatedFormat('d-m-Y H:i:s') }}
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="text-dark mb-4">
                    Dear {{ $order->user->name }},
                    <br />
                    Pesanan Anda dengan nomor transaksi <span class="fw-bold">#{{ $order->transaction->invoice }}</span>
                    sudah berhasil dilakukan
                    pada tanggal
                    {{ $order->transaction->created_at->translatedFormat('d-m-Y H:i:s') }}. Mohon lakukan pembayaran
                    sejumlah <span class="fw-bold">{{ Helper::formatCurrency($order->amount, 0, ',', '.') }}</span>
                    dalam jangka waktu kurang dari 1 jam. Jika tidak, pesanan Anda akan dibatalkan.
                    <br />
                    Silahkan Klik link di bawah ini untuk melakukan pembayaran :
                    <div class="py-4 text-center">
                        <a href="{{ $order->transaction->url }}" class="fs-1 text-decoration-none">
                            Bayar Sekarang
                        </a>
                    </div>
                </div>
                <!-- Invoice table-->
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
                                                <h6 class="mb-0 text-nowrap fw-bold">{{ $item->product->name }}</h6>
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
