<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Http\Controllers\API\BaseController;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
          'shipping.etd' => 'nullable|string|max:50',
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
        "amount" => $amount + $validated['shipping']['cost'],
        'user_id' => $request->user()->id,
        'note' => $validated['note'],
      ]);

      $order->order_items()->createMany($validated['items']);

      $order->shipping()->create([
        'name' => $validated['shipping']['name'],
        'code' => $validated['shipping']['code'],
        'description' => $validated['shipping']['description'],
        'cost' => $validated['shipping']['cost'],
        'etd' => $validated['shipping']['etd'],

        'recipient_name' => $request->user()->address->name,
        'address' => $request->user()->address->address,
        'phone_number' => $request->user()->address->phone_number,
        'province_name' => $request->user()->address->province_name,
        'city_name' => $request->user()->address->city_name,
        'district_name' => $request->user()->address->district_name,
        'subdistrict_name' => $request->user()->address->subdistrict_name,
        'zip_code' => $request->user()->address->zip_code,
        'address_label' => $request->user()->address->address_label,
        'destination_id' => $request->user()->address->destination_id,
        'note' => $request->user()->address->note,
      ]);

      $line_items[] = [
        'id' => 'SHIPPING',
        'name' => 'Biaya Pengiriman',
        'quantity' => 1,
        'price' => $validated['shipping']['cost'],
      ];
      $amount += $validated['shipping']['cost'];

      $secretKey = config('doku.secret_key');
      $clientId = config('doku.client_id');
      $requestId = (string) Str::uuid();
      $requestDate = now()->toIso8601ZuluString();
      $targetPath = "/checkout/v1/payment";
      $requestBody = [
        'order' => [
          'amount' => $amount,
          'invoice_number' => $invoice,
          "currency" => "IDR",
          "language" => "ID",
          "callback_url" => route("user.order.show", ["order_number" => $order->order_number]),
          "callback_url_cancel" => route("user.order.show", ["order_number" => $order->order_number]),
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
        ],
        'additional_info' => [
          "allow_tenor" => [0, 3, 6, 12],
          "doku_wallet_notify_url" => "https://goose-honest-newly.ngrok-free.app/api/payments/notifications",
          "override_notification_url" => "https://goose-honest-newly.ngrok-free.app/api/payments/notifications",
        ]
      ];

      $digest = $this->generateDigest($requestBody);
      $signature = $this->generateSignature(
        $clientId,
        $requestId,
        $targetPath,
        $digest,
        $secretKey,
        $requestDate
      );

      $doku = Http::withHeaders([
        'Client-Id' => $clientId,
        'Request-Id' => $requestId,
        'Request-Timestamp' => $requestDate,
        'Signature' => $signature,
        'Digest' => $digest
      ])->post(config('doku.url') . $targetPath, $requestBody);

      if (!$doku->successful()) {
        throw new Exception("Gagal menghubungi Doku API: " . $doku->body());
      }

      $paymentUrl = $doku->json()['response']['payment']['url'];

      $order->transaction()->create([
        'invoice' => $invoice,
        'url' => $paymentUrl,
      ]);

      DB::commit();
      return $this->sendResponse($paymentUrl, "Checkout Berhasil");
    } catch (CustomException $e) {
      DB::rollBack();

      return $this->sendError(error: $e->getMessage(), code: 400);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());
      return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
    }
  }

  function generateSignature($clientId, $requestId, $requestTarget, $digest, $secret, $requestTimestamp)
  {
    // Prepare Signature Component
    $componentSignature = "Client-Id:" . $clientId;
    $componentSignature .= "\n";
    $componentSignature .= "Request-Id:" . $requestId;
    $componentSignature .= "\n";
    $componentSignature .= "Request-Timestamp:" . $requestTimestamp;
    $componentSignature .= "\n";
    $componentSignature .= "Request-Target:" . $requestTarget;

    if ($digest) {
      $componentSignature .= "\n";
      $componentSignature .= "Digest:" . $digest;
    }

    // Calculate HMAC-SHA256
    $hmac = hash_hmac('sha256', $componentSignature, $secret, true);
    $signature = base64_encode($hmac);

    return "HMACSHA256=" . $signature;
  }

  function generateDigest($body)
  {
    $hash = hash('sha256', json_encode($body), true);
    return base64_encode($hash);
  }
}