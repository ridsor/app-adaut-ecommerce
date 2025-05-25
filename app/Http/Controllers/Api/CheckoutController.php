<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends BaseController
{
  public function productCheckout(Request $request)
  {
    try {
      Config::$serverKey = config('midtrans.server_key');
      Config::$isProduction = config('midtrans.is_production');
      Config::$isSanitized = config('midtrans.is_sanitized');
      Config::$is3ds = config('midtrans.is_3ds');

      $validator = Validator::make(
        $request->all(),
        [
          'items' => 'required|array',
          'items.*.product_id' => 'required',
          'items.*.quantity' => 'required|integer|min:1',
        ],
        [
          'items.*.product_id.required' => 'Item ID wajib diisi.',
          'items.*.product_id.string' => 'Item ID harus berupa teks.',
          'items.*.quantity.required' => 'Kuantitas wajib diisi.',
          'items.*.quantity.integer' => 'Kuantitas harus berupa angka.',
          'items.*.quantity.min' => 'Kuantitas minimal adalah 1.',
        ]
      );

      if ($validator->fails()) {
        return $this->sendError(error: 'Validasi gagal', errorMessages: $validator->error(), code: 500);
      }

      DB::beginTransaction();

      $validated = $validator->validated();

      $timestamp = time(); // Waktu saat ini dalam detik
      $randomNumber = rand(100, 999); // Angka acak antara 100 dan 999
      $order_id = 'ORDER' . $timestamp . $randomNumber;
      $amount = 0;
      $item_details = [];

      $order_items = [];
      foreach ($validated['items'] as $item) {
        $price = 0;
        $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
        if (!$product) {
          DB::rollBack();
          return $this->sendError("Checkout gagal, data tidak valid", code: 422);
        }
        $product->decreaseStock($item['quantity']);

        $price = $product->price;

        $item_details[] = [
          'id' => $product->id,
          'price' => $product->price,
          'name' => $product->name,
          'quantity' => $item['quantity'],
        ];


        $item['order_id'] = $order_id;
        $amount = $amount + ($item['quantity'] * $price);

        $order_items[] = $item;
      }

      $params = [
        'transaction_details' => [
          'order_id' => $order_id,
          'gross_amount' => $amount,
        ],
        'customer_details' => [
          'first_name' => Auth::user()->first_name,
          'last_name' => Auth::user()->last_name,
          'email' => Auth::user()->email,
        ],
        'item_details' => $item_details
      ];

      $snapToken = Snap::getSnapToken($params);

      $order = Order::create([
        'id' => $order_id,
        'user_id' => Auth::user()->id,
        "total_amount" => $amount,
        "order_date" => now(),
      ]);
      $order->products()->createMany($order_items);

      DB::commit();

      return $this->sendResponse($snapToken, "Checkout Berhasil");
    } catch (\Exception $e) {
      DB::rollBack();

      return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
    }
  }
}
