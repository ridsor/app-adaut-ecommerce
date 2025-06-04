<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->authorize('isUser');
    }

    public function produkCheckout(Request $request)
    {
        $origin = User::where('role', 'admin')->first()->address?->destination_id;
        return view('checkout', [
            'title' => 'Checkout Produk',
            "doku_client_key" => config('doku.client_key'),
            "user" => $request->user(),
            'origin' => $origin
        ]);
    }
}