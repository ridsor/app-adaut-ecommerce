<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    $products = Product::search($request->query('search'))->query(fn($query) => Product::filters($query, request(['sort', 'category', 'max_price', 'min_price', 'stock'])))->get();

    return view('product.index', [
      'title' => "Produk",
      "products" => $products
    ]);
  }

  public function show($id)
  {
    $product = Product::with([
      "reviews" => function($query) {
        $query->select(["rating", "item_id", "comment", "user_id"]);
      },
      'reviews.user'])->withCount('reviews')->withAvg('reviews', 'rating')->find($id);

    return view('product.show', [
      "product" => $product
    ]);
  }
  
  public function checkout()
  {
    return view('checkout', [
      "title" => "Checkout",
      "midtrans_client_key" => env("MIDTRANS_CLIENT_KEY")
    ]);
  }

  public function checkoutPost(Request $request)
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
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Validation failed',
                    "errors" => $validator->errors()
                ], 500);
            }


            DB::beginTransaction();

            $validated = $validator->validated();

            $timestamp = time(); // Waktu saat ini dalam detik
            $randomNumber = rand(100, 999); // Angka acak antara 100 dan 999
            $order_id = 'ORDER' . $timestamp . $randomNumber;
            $amount = 0;
            $item_details = [];
            $order_items = array_map(function ($item)  use (&$item_details, &$amount, $order_id) {
                $price = 0;
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
                if (!$product) {
                    throw new Exception("Produk tidak ditemukan.");
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
                return $item;
            }, $validated['items']);

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

            return response()->json($snapToken);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "status" => "error",
                "message" => "Something went wrong",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
}
