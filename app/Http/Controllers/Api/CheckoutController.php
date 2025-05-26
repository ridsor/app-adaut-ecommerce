<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Midtrans\Config;
use Illuminate\Support\Str;
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
          'items.*.product_id' => ['required', Rule::exists('products', 'id')->whereNull('deleted_at')],
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
        return $this->sendError(error: 'Validasi gagal', errorMessages: $validator->errors(), code: 500);
      }
      DB::beginTransaction();

      $validated = $validator->validated();

      $amount = 0;
      $total_weight = 0;
      $item_details = [];

      foreach ($validated['items'] as $item) {
        $price = 0;
        $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

        $product->decreaseStock($item['quantity']);

        $price = $product->price;

        $item_details[] = [
          'id' => $product->id,
          'price' => $product->price,
          'name' => $product->name,
          'category' => $product->category->name,
          'url' => route("product.detail", ["slug" => $product->slug]),
          'quantity' => $item['quantity'],
        ];

        $amount = $amount + ($item['quantity'] * $price);
        $total_weight  += $product->weight;
      }

      $prefix = 'INV';
      $random = Str::upper(Str::random(5)); 
      $invoice = $prefix . '-' . date('Ymd') . '-' . $random;
      $order = Order::create([
        "amount" => $amount,
        'user_id' => $request->user()->id,
      ]);

      $order->order_items()->createMany($validated['items']);

      $order->shipping()->create([
        'name' => $request->shipping_name,
        'code' => $request->shipping_code,
        'description' => $request->shipping_description,
        'cost' => $request->shipping_cost,
        'etd' => $request->shipping_etd
      ]);

      $params = [
        'transaction_details' => [
          'order_id' => $invoice,
          'gross_amount' => $amount,
        ],
        'customer_details' => [
          'name' => $request->user()->name,
          'email' => $request->user()->email,
          'phone' => $request->user()->profile->phone_number,
          'shipping_address' => [
            'name' => $request->user()->address->name,
            'address' => $request->user()->address->address,
            'city' => $request->user()->address->city_name,
            'zip_code' => $request->user()->address->zip_code,
            "country_code" => "IDN"
          ]
        ],
        'item_details' => $item_details
      ];

      $snap_token = Snap::getSnapToken($params);

      $order->transaction()->create([
        'invoice' => $invoice,
        'snap_token' => $snap_token
      ]);

      DB::commit();

      return $this->sendResponse($snap_token, "Checkout Berhasil");
    } catch (\Exception $e) {
      dd($e);
      DB::rollBack();

      return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
    }
  }
}