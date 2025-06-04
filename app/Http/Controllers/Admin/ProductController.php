<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ItemNotFoundException;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
  public function __construct()
  {
    $this->authorize('isAdmin');
  }

  public function index(Request $request)
  {
    $products = Product::search($request->query('search'))->query(fn($query) => $query->with(['category'])->select(['category_id', 'id', 'name', 'image', 'slug', 'price', 'stock'])->filters(request(['availability', 'sort', 'categories', 'max_price', 'min_price', 'stock', 'rating'])))->paginate(10);

    $categories = Category::select(['name', 'id', 'slug'])->withCount('products')->get();
    $sort = [
      [
        "value" => "asc",
        "text" => "A-Z",
      ],
      [
        "value" => "desc",
        "text" => "Z-A",
      ],
      [
        "value" => "latest",
        "text" => "Terbaru",
      ],
      [
        "value" => "oldest",
        "text" => "Terlama",
      ],
      [
        "value" => "lowest_price",
        "text" => "Termurah",
      ],
      [
        "value" => "highest_price",
        "text" => "Termahal",
      ],
      [
        "value" => "review",
        "text" => "Ulasan",
      ],
      [
        "value" => "bestsellers",
        "text" => "Terlaris",
      ],
    ];
    $total_items = Product::count();
    return view('admin.product.index', [
      'title' => "Produk",
      "products" => $products,
      'categories' => $categories,
      'sort' => $sort,
      "total_items" => $total_items
    ]);
  }

  public function show($slug)
  {
    $product = Product::withCount('reviews')->withAvg('reviews', 'rating')->where("slug", $slug)->first();

    if (!$product) {
      throw new ItemNotFoundException($slug);
    }

    return view('admin.product.show', [
      'title' => 'Produk ' . $product->name,
      "product" => $product,
    ]);
  }

  public function create()
  {
    $categories = Category::all();

    return view('admin.product.create', [
      'title' => 'Tambah Produk',
      "categories" => $categories
    ]);
  }

  public function store(Request $request)
  {
    $request->validate(
      [
        "name" => "required",
        "description" => "required",
        "price" => "required|numeric",
        "stock" => "required|integer",
        "weight" => "required|integer",
        "category_id" => 'required|exists:categories,id',
        "image" => "required|image|mimes:jpeg,png,jpga,webp|max:1048",
      ],
      [
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
        'image.max' => 'Ukuran gambar maksimal 1MB',

        'category_id.required' => 'Kategori wajib dipilih',
        'category_id.exists' => 'Kategori yang dipilih tidak valid'
      ]
    );

    try {
      $image = $request->file('image')->store('gambar/produk');

      Product::create([
        "name" => $request->name,
        "price" => $request->price,
        "stock" => $request->stock,
        "weight" => $request->weight,
        "image" => $image,
        "description" => $request->description,
        "category_id" => $request->category_id,
      ]);

      return redirect(route('admin.product.index'))->with('success', 'Produk berhasil dibuat');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal membuat produk');
    }
  }

  public function edit($slug)
  {
    $product = Product::where('slug', $slug)->first();

    if (!$product) {
      throw new ItemNotFoundException($slug);
    }

    $categories = Category::select(['id', 'name'])->get();

    return view('admin.product.edit', [
      'title' => 'Edit Produk ' . $product->name,
      "product" => $product,
      "categories" => $categories
    ]);
  }

  public function update(Request $request, $slug)
  {
    $rules = [
      "name" => "required",
      "description" => "required",
      "price" => "required|numeric",
      "stock" => "required|integer",
      "weight" => "required|integer",
      "category_id" => 'required|exists:categories,id',
    ];

    if ($request->hasFile('image')) {
      $rules['image'] = "required|image|mimes:jpeg,png,jpg|max:1048";
    }

    $request->validate($rules, [
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
      'image.max' => 'Ukuran gambar maksimal 1MB',

      'category_id.required' => 'Kategori wajib dipilih',
      'category_id.exists' => 'Kategori yang dipilih tidak valid'
    ]);

    try {
      $product = Product::where('slug', $slug)->firstOrFail();

      $image = $product->image;

      if ($request->hasFile('image')) {
        if ($image) {
          Storage::delete($image);
        }

        $image = $request->file('image')->store('gambar/produk');
      }

      $product->update([
        "name" => $request->name,
        "description" => $request->description,
        "price" => $request->price,
        "stock" => $request->stock,
        "weight" => $request->weight,
        "category_id" => $request->category_id,
        "image" => $image,
      ]);

      return redirect(route('admin.product.index'))->with('success', 'Produk berhasil diperbarui');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal mengedit produk');
    }
  }

  public function destroy($slug)
  {
    try {
      $product = Product::where('slug', $slug)->firstOrFail();
      $product->delete();

      return back()->with('success', 'Produk berhasil dihapus');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal menghapus produk');
    }
  }
}
