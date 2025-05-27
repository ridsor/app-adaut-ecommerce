<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CheckoutController extends BaseController
{
  public function productCheckout(Request $request)
  {
    try {

      $validator = Validator::make(
        $request->all(),
        [
          'note' => 'nullable|string|max:255',
          'shipping.name' => 'required|string|max:255',
          'shipping.code' => 'required|string|max:50',
          'shipping.description' => 'nullable|string|max:255',
          'shipping.cost' => 'required|numeric|min:0',
          'shipping.etd' => 'required|string|max:50',
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
      $line_items = [];

      foreach ($validated['items'] as $item) {
        $price = 0;
        $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

        $product->decreaseStock($item['quantity']);

        $price = $product->price;

        $line_items[] = [
          'id' => $product->id,
          'name' => $product->name,
          'quantity' => $item['quantity'],
          'price' => $product->price,
          'category' => $product->category->name,
          'sku' => $product->sku,
          'url' => route("product.detail", ["slug" => $product->slug]),
          'image_url' => asset('storage/' . $product->image),
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
        'name' => $validated['shipping']['name'],
        'code' => $validated['shipping']['code'],
        'description' => $validated['shipping']['description'],
        'cost' => $validated['shipping']['cost'],
        'etd' => $validated['shipping']['etd']
      ]);
      // $line_items[] = [
      //   'id' => 'SHIPPING',
      //   'name' => 'Biaya Pengiriman',
      //   'quantity' => 1,
      //   'price' => $validated['shipping']['cost'],
      // ];

      $secretKey = config('doku.secret_key');
      $clientId = config('doku.client_id');
      $requestId = Str::uuid()->toString();
      $requestDate = now()->toIso8601ZuluString();
      $targetPath = "/checkout/v1/payment";
      $requestBody = [
        'order' => [
          'amount' => $amount,
          'invoice_number' => $invoice,
          "currency" => "IDR",
          "language" => "ID",
          "callback_url" => config('app.url'),
          "callback_url_cancel" => config('app.url'),
          "auto_redirect" => true,
          'line_items' => $line_items
        ],
        "payment" => [
          "payment_due_date" => 60
        ],
        'customer' => [
          'id' => $request->user()->id,
          'name' => $request->user()->name,
          'email' => $request->user()->email,
          'phone' => $request->user()->address->phone_number,
          'address' => $request->user()->address->address,
          'postcode' => $request->user()->address->zip_code,
          'city' => $request->user()->address->city_name,
          'state' => $request->user()->address->province_name,
          'country' => 'ID',
        ],
        'shipping_address' => [
          'name' => $request->user()->address->name,
          'address' => $request->user()->address->address,
          'phone' => $request->user()->address->phone_number,
          'city' => $request->user()->address->city_name,
          'postal_code' => $request->user()->address->zip_code,
          "country_code" => "IDN"
        ]
      ];
      $digestValue = base64_encode(hash('sha256', json_encode($requestBody, JSON_UNESCAPED_SLASHES), true));

      $componentSignature =
        "Client-Id:" . $clientId . "\n" .
        "Request-Id:" . $requestId . "\n" .
        "Request-Timestamp:" . $requestDate . "\n" .
        "Request-Target:" . $targetPath . "\n" .
        "Digest:" . $digestValue;

      // Generate the HMAC SHA256 signature
      $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

      // Only use the HMACSHA256=... portion for the Signature header
      $headerSignature =  "Client-Id:" . $clientId . "\n" .
        "Request-Id:" . $requestId . "\n" .
        "Request-Timestamp:" . $requestDate . "\n" .
        // Prepend encoded result with algorithm info HMACSHA256=
        "Signature:" . "HMACSHA256=" . $signature;

      $doku = Http::withHeaders([
        'Client-Id' => $clientId,
        'Request-Id' => $requestId,
        'Request-Timestamp' => $requestDate,
        'Signature' => 'HMACSHA256=' . $signature
      ])->asForm()->post(config('doku.url') . $targetPath, $requestBody);

      if ($doku->status() !== 200) {
        throw new Exception("Gagal menghubungi Doku API: " . $doku->body());
      }

      $paymentUrl = $doku->json()['response']['payment']['url'];

      $order->transaction()->create([
        'invoice' => $invoice,
        'url' => $paymentUrl,
      ]);

      DB::commit();
      return $this->sendResponse($paymentUrl, "Checkout Berhasil");
    } catch (\Exception $e) {
      dd($e);
      DB::rollBack();

      return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
    }
  }
}
