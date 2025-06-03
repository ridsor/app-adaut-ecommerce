<?php

namespace App\Http\Controllers;

use App\Exceptions\ItemNotFoundException;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
