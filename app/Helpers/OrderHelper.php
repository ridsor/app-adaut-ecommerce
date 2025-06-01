<?php

namespace App\Helpers;

class OrderHelper
{

    public static function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public static function getStatusClass($status)
    {
        return match ($status) {
            'unpaid' => 'warning',
            'packed' => 'info',
            'submitted' => 'primary',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the label for an order status.
     */
    public static function getStatusLabel($status)
    {
        return match ($status) {
            'unpaid' => 'Belum Bayar',
            'packed' => 'Dikemas',
            'submitted' => 'Dikirim',
            'completed' => 'Selesai',
            'failed' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    /**
     * Get the CSS class for a payment status.
     */
    public static function getPaymentStatusClass($status)
    {
        return match ($status) {
            'success' => 'success',
            'failed' => 'danger',
            'expired' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get the label for a payment status.
     */
    public static function getPaymentStatusLabel($status)
    {
        return match ($status) {
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            default => 'Unknown',
        };
    }
}
