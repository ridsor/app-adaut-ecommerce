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

  public function create()
  {   
    $categories = Category::all();

    return view('admin.product.create', [
      "categories" => $categories
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      "name" => "required",
      "description" => "required",
      "price" => "required|numeric",
      "stock" => "required|integer",
      "category_id" => 'required|exists:categories,id',
      "image" => "required|image|mimes:jpeg,png,jpg|max:2048",
    ],
      [
        'product_id.required' => 'ID produk wajib diisi',
        'quantity.required' => 'Jumlah produk wajib diisi',
        'quantity.integer' => 'Jumlah produk harus berupa angka bulat',
        'quantity.min' => 'Jumlah produk minimal harus 1',
        
        'name.required' => 'Nama produk wajib diisi',
        'description.required' => 'Deskripsi produk wajib diisi',
        'price.required' => 'Harga produk wajib diisi',
        'price.numeric' => 'Harga harus berupa angka',
        'stock.required' => 'Stok produk wajib diisi',
        'stock.integer' => 'Stok harus berupa angka bulat',
        'category_id.required' => 'Kategori produk wajib dipilih',
        'image.required' => 'Gambar produk wajib diunggah',
        'image.image' => 'File harus berupa gambar',
        'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
        'image.max' => 'Ukuran gambar maksimal 2MB',

        'category_id.required' => 'Kategori wajib dipilih',
        'category_id.exists' => 'Kategori yang dipilih tidak valid'
    ]);

    $imageName = time() . '.' . $request->image->extension();
    if (!Storage::disk('public')->exists('gambar/produk')) {
        Storage::disk('public')->makeDirectory('gambar/produk');
    }
    $imageStorage = Storage::disk('public')->putFileAs('gambar/produk', $request->image, $imageName);
    $image = Storage::url($imageStorage);

    Product::create([
      "name" => $request->name,
      "price" => $request->price,
      "stock" => $request->stock,
      "image" => $image,
      "description" => $request->description,
      "category_id" => $request->category_id,
    ]);

    return back()->with('success', 'Produk berhasil dibuat');
  }

  public function edit($id)
  {
    $product = Product::find($id);
    $categories = Category::all();

    return view('admin.product.edit', [
      "product" => $product,
      "categories" => $categories
    ]);
  }
  public function update($id, Request $request)
  {
    $rules = [
      "name" => "required",
      "description" => "required",
      "price" => "required|integer",
      "stock" => "required|integer",
      "category_id" => 'required|exists:categories,id',
    ];

    if ($request->hasFile('image')) {
      $rules['image'] = "required|image|mimes:jpeg,png,jpg|max:2048";
    }

    $request->validate($rules,[
      'product_id.required' => 'ID produk wajib diisi',
      'quantity.required' => 'Jumlah produk wajib diisi',
      'quantity.integer' => 'Jumlah produk harus berupa angka bulat',
      'quantity.min' => 'Jumlah produk minimal harus 1',
      
      'name.required' => 'Nama produk wajib diisi',
      'description.required' => 'Deskripsi produk wajib diisi',
      'price.required' => 'Harga produk wajib diisi',
      'price.numeric' => 'Harga harus berupa angka',
      'stock.required' => 'Stok produk wajib diisi',
      'stock.integer' => 'Stok harus berupa angka bulat',
      'category_id.required' => 'Kategori produk wajib dipilih',
      'image.required' => 'Gambar produk wajib diunggah',
      'image.image' => 'File harus berupa gambar',
      'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
      'image.max' => 'Ukuran gambar maksimal 2MB',

      'category_id.required' => 'Kategori wajib dipilih',
      'category_id.exists' => 'Kategori yang dipilih tidak valid'
    ]);

    $product = Product::find($id);

    $image = $request->image;

    if ($request->hasFile('image')) { 
      $imageName = time() . '.' . $request->image->extension();
      if (!Storage::disk('public')->exists('gambar/produk')) {
          Storage::disk('public')->makeDirectory('gambar/produk');
      }
      $imageStorage = Storage::disk('public')->putFileAs('images/produk', $request->image, $imageName);
      $image = Storage::url($imageStorage);
    }

    $product->update([
      "name" => $request->name,
      "description" => $request->description,
      "price" => $request->price,
      "stock" => $request->stock,
      "category_id" => $request->category_id,
      "image" => $image,
    ]);

    return back()->with('success', 'Produk berhasil diperbarui');
  }

  public function destroy($id)
  {
    $product = Product::find($id);
    $product->delete();

    return back()->with('success', 'Produk berhasil dihapus');
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
