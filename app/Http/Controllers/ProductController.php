<?php

namespace App\Http\Controllers;

use App\Exceptions\ItemNotFoundException;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function show(Request $request, $slug)
  {
    $product = Product::with([
      "reviews" => function ($query) {
        // $query->;
      },
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
