<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
  public function __construct()
  {
    $this->authorize('isAdmin');
  }

  public function index(Request $request)
  {
    $products = Product::search($request->query('search'))->query(fn($query) => Product::filters($query, request(['sort', 'category', 'max_price', 'min_price', 'stock'])))->get();

    return view('product.index', [
      'title' => "Produk",
      "products" => $products
    ]);
  }

  public function show($slug)
  {
    $product = Product::with([
      "reviews" => function($query) {
        $query->select(["rating", "product_id", "comment", "user_id", "created_at"]);
      },
      'reviews.user' => function($query) {
        $query->select(['id','name']);
      }])->withCount('reviews')->withAvg('reviews', 'rating')->where("slug", $slug)->first();

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
    try {
      $request->validate([
        "name" => "required",
        "description" => "required",
        "price" => "required|numeric",
        "stock" => "required|integer",
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
      ]);
  
      $image = FileHelper::uploadFile($request->image, 'gambar/produk');
      
      Product::create([
        "name" => $request->name,
        "price" => $request->price,
        "stock" => $request->stock,
        "image" => $image,
        "description" => $request->description,
        "category_id" => $request->category_id,
      ]);
  
      return back()->with('success', 'Produk berhasil dibuat');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal membuat produk');
    }
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
    try {
      $rules = [
        "name" => "required",
        "description" => "required",
        "price" => "required|integer",
        "stock" => "required|integer",
        "category_id" => 'required|exists:categories,id',
      ];
  
      if ($request->hasFile('image')) {
        $rules['image'] = "required|image|mimes:jpeg,png,jpg|max:1048";
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
        'image.max' => 'Ukuran gambar maksimal 1MB',
  
        'category_id.required' => 'Kategori wajib dipilih',
        'category_id.exists' => 'Kategori yang dipilih tidak valid'
      ]);
  
      $product = Product::find($id);
  
      $image = $request->image;
  
      if ($request->hasFile('image')) { 
        $image = FileHelper::uploadFile($request->image, 'images/product');
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
    catch (\Exception $e) {
      return back()->with('error', 'Gagal menghapus memperbarui produk');
    }
  }

  public function destroy($id)
  {
    try {
      $product = Product::findOrFail($id);
      if ($product->image) {
        FileHelper::deleteFileByUrl($product->image);
      }
      $product->delete();
      
      return back()->with('success', 'Produk berhasil dihapus');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal menghapus produk');
    }

  }
}