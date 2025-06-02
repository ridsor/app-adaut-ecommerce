<?php

namespace App\Helpers;

class ProductHelper
{

    public static function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
