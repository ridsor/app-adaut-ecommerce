<?php

namespace App\Http\Controllers;

use App\Exceptions\ItemNotFoundException;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function search(Request $request)
  {
    $products = Product::search($request->query('value'))->query(fn($query) => Product::filters($query, request(['availability', 'sort', 'categories', 'max_price', 'min_price', 'stock', 'rating'])))->paginate(10);
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

    return view('search', [
      ' title ' => "Produk " . $request->query(' value '),
      "products" => $products,
      "categories" => $categories,
      "sort" => $sort
    ]);
  }

  public function show(Request $request, $slug)
  {
    $product = Product::with([
      "reviews" => function ($query) {
        $query->select(["rating", "product_id", "comment", "user_id", "created_at", 'id']);
      },
      'reviews.user' => function ($query) {
        $query->select(['username', 'id']);
      },
      'reviews.review_media' => fn($query) => $query->select(['review_id', 'file_path', 'id'])
    ])->withCount('reviews')->withAvg('reviews', 'rating')->where("slug", $slug)->first();

    if (!$product) {
      throw new ItemNotFoundException($slug);
    }

    return view('product.show', [
      "title" => $product->name,
      "product" => $product,
      'user' => $request->user()
    ]);
  }
}
