@php
    use App\Helpers\Helper;
@endphp


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Invoice Email</title>
    <style>
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
        }

        .label {
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .value {
            font-size: 12px;
            color: #888;
            margin-bottom: 10px;
        }
    </style>
</head>

<body style="margin:0; padding:0; font-family:Arial, sans-serif;">
    <center>
        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 0;">
            <tr>
                <td align="center">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="620"
                        style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
                        <!-- HEADER -->
                        <tr>
                            <td style="background-color:#2563eb; padding:24px 32px; color:#ffffff;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="left" style="vertical-align:middle;">
                                            <table cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding-right: 16px;">
                                                        <img src="{{ env('APP_URL') }}/assets/img/favicon.png"
                                                            alt="logo" width="48" style="display:block" />
                                                    </td>
                                                    <td style="font-size:18px; font-weight:600; color:#ffffff;">Adaut
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td align="right" style="font-size:12px; opacity:0.8;">
                                            <div style="font-size:28px; font-weight:700; margin-bottom: 8px">Pesanan
                                            </div>
                                            <div style="margin-bottom: 8px">{{ $order->transaction->invoice }}</div>
                                            <div>{{ $order->transaction->created_at->translatedFormat('d-m-Y H:i:s') }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- BODY -->
                        <tr>
                            <td style="padding:32px; font-size:14px; color:#333333;">
                                <p style="margin:0 0 16px;">
                                    Pesanan dengan nomor transaksi <strong>{{ $order->transaction->invoice }}</strong>
                                    telah dibuat oleh
                                    pelanggan.
                                    Silakan lakukan pengecekan dan tindak lanjut sesuai prosedur operasional.
                                </p>
                                <p style="margin:0 0 24px;">Klik tombol di bawah untuk melihat detail pesanan:</p>

                                <p style="text-align:center;">
                                    <a href="{{ route('admin.order.show', ['pesanan' => $order->order_number]) }}"
                                        style="background-color:#2563eb; color:#ffffff; text-decoration:none; padding:12px 24px; border-radius:4px; display:inline-block; font-weight:600;">
                                        Detail Pesanan
                                    </a>
                                </p>

                                <!-- Detail Pelanggan -->
                                <div class="section-title">DETAIL PELANGGAN</div>
                                <div class="label">Nama Pelanggan</div>
                                <div class="value">RYAN SYUKUR</div>

                                <div class="label">Catatan</div>
                                <div class="value">-</div>

                                <!-- Informasi Pengiriman -->
                                <div class="section-title">INFORMASI PENGIRIMAN</div>
                                <div class="label">Nama Penerima</div>
                                <div class="value">RYAN SYUKUR</div>

                                <div class="label">Alamat</div>
                                <div class="value">
                                    Jln Ir M Putuhena<br>
                                    WAYAME, TELUK AMBON, AMBON, MALUKU, 97234<br>
                                    Blok 3 Baru
                                </div>

                                <div class="label">No Telepon</div>
                                <div class="value">082211981226</div>

                                <div class="label">Metode Pengiriman</div>
                                <div class="value">Jalur Nugraha Ekakurir (JNE)</div>

                                <!-- Order Details -->
                                <h3 style="margin-top:40px; font-size:16px; font-weight:700;">RINCIAN PESANAN</h3>
                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="font-size:14px; margin-top:12px;">
                                    <thead>
                                        <tr style="background-color:#f3f4f6; text-align:left;">
                                            <th style="padding:8px;">Produk</th>
                                            <th style="padding:8px; text-align:right;">Harga</th>
                                            <th style="padding:8px; text-align:right;">Jumlah</th>
                                            <th style="padding:8px; text-align:right;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->order_items as $item)
                                            <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td style="padding:12px;">
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="padding-right:12px;">
                                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                    alt=""
                                                                    style="width:56px; height:56px; border-radius:4px; object-fit:cover;" />
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                {{ $item->product->name }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="text-align:right; padding:12px;">
                                                    {{ Helper::formatCurrency($item->product->price, 0, ',', '.') }}
                                                </td>
                                                <td style="text-align:right; padding:12px;">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td style="text-align:right; padding:12px;">
                                                    {{ Helper::formatCurrency($item->product->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Price Summary -->
                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="margin-top:32px; font-size:14px; color:#6b7280;">
                                    <tr>
                                        <td style="padding:6px 0;">Subtotal:</td>
                                        <td style="padding:6px 0; text-align:right;">
                                            {{ Helper::formatCurrency($order->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;">Subtotal Pengiriman:</td>
                                        <td style="padding:6px 0; text-align:right;">
                                            {{ Helper::formatCurrency($order->shipping->cost, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0; font-weight:700; color:#111827;">Total Pembayaran:
                                        </td>
                                        <td style="padding:6px 0; text-align:right; font-weight:700; color:#22c55e;">
                                            {{ Helper::formatCurrency($order->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- FOOTER -->
                        <tr>
                            <td align="center" style="font-size:12px; color:#9ca3af; padding:24px;">
                                Adaut &copy; 2025
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
