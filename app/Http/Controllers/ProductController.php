<?php

namespace App\Http\Controllers;

use App\Exceptions\ItemNotFoundException;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function show(Request $request, $slug)
  {
    $product = Product::withCount('reviews')->withAvg('reviews', 'rating')->withSum([
      'order_items as total_sold'
    ], 'quantity')->where("slug", $slug)->first();

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
