<?php

namespace App\Helpers;

class Helper
{

    public static function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}