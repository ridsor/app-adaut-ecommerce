    @extends('layouts.user.app')

    @php
        use App\Helpers\OrderHelper;
        use App\Helpers\Helper;
    @endphp

    @section('content')

        <div class="container-xl p-0 px-md-2">
            <div class="row flex-wrap">
                <div class="col-lg-8">
                    <!-- Order Items -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Produk yang dibeli</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->order_items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="product-image ratio ratio-1x1 overflow-hidden"
                                                                style="width: 60px">
                                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                    alt="" style="background-position: center"
                                                                    class="h-100 object-fit-contain" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                            @if ($item->note)
                                                                <small class="text-muted">Catatan:
                                                                    {{ $item->note }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end" style="white-space: nowrap">
                                                    {{ Helper::formatCurrency($item->product->price, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">
                                                    {{ Helper::formatCurrency($item->product->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Order Summar -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6 fw-bold">Status Pesanan</div>
                                <div class="col-6 text-end">
                                    <span class="badge bg-{{ OrderHelper::getStatusClass($order->status) }}">
                                        {{ OrderHelper::getStatusLabel($order->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 fw-bold">No. Pesanan</div>
                                <div class="col-6 text-end">{{ $order->order_number }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 fw-bold">Tanggal Pesanan</div>
                                <div class="col-6 text-end">{{ $order->created_at->translatedFormat('d F Y') }}</div>
                            </div>
                            @if ($order->awb)
                                <div class="row mb-2">
                                    <div class="col-6 fw-bold">No. Resi</div>
                                    <div class="col-6 text-end">{{ $order->awb }}</div>
                                </div>
                            @endif
                            <hr>
                            <div class="row mb-2">
                                <div class="col-6">Subtotal Produk</div>
                                <div class="col-6 text-end">
                                    {{ Helper::formatCurrency($order->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Subtotal Pengiriman</div>
                                <div class="col-6 text-end">
                                    {{ Helper::formatCurrency($order->shipping->cost, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 fw-bold">Total Pembayaran</div>
                                <div class="col-6 text-end fw-bold text-primary">
                                    {{ Helper::formatCurrency($order->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @if ($order->status == 'unpaid' || $order->status == 'completed')
                            <div class="card-footer">
                                <div class="d-flex gap-2">
                                    @if ($order->status == 'unpaid')
                                        <a href="{{ $order->transaction->url }}" class="btn btn-success w-100">Bayar
                                            Sekarang</a>
                                    @endif
                                    @if ($order->status == 'completed')
                                        <a href="{{ route('user.review.product.index', ['order_number' => $order->order_number]) }}"
                                            class="btn btn-primary w-100">Nilai</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Customer Notes -->
                    @if ($order->note)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Catatan Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $order->note }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Informasi Pe flex-wrapngiriman -->
                <div class="col mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-truck me-2"></i>Informasi Pengiriman
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($order->shipping)
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <p class="mb-1 text-dark fw-bold">
                                            <span>{{ $order->shipping->recipient_name }}</span>
                                        </p>
                                        <p>{{ $order->shipping->phone_number }}</p>
                                        <p class="fs-responsive mb-0">
                                            {{ $order->shipping->address }}
                                        </p>
                                        <p class="fs-responsive m-0">
                                            {{ $order->shipping->address_label }}
                                        </p>
                                        <p class="fs-responsive m-0">
                                            {{ $order->shipping->note }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Kurir:</label>
                                            <p class="mb-0 fw-medium">{{ $order->shipping->name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Kode Layanan:</label>
                                            <p class="mb-0">{{ $order->shipping->code }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Deskripsi:</label>
                                            <p class="mb-0">{{ $order->shipping->description }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Estimasi Pengiriman:</label>
                                            <p class="mb-0">{{ $order->shipping->etd }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label text-muted">Biaya Pengiriman:</label>
                                            <p class="mb-0 fw-medium text-primary">
                                                {{ Helper::formatCurrency($order->shipping->cost) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-shipping-fast fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">Informasi pengiriman belum tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
