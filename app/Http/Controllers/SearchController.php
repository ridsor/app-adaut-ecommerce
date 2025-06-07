<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $products = Product::search($request->query('search'))->query(fn($query) =>
        $query->select(['id', 'name', 'image', 'slug', 'price', 'stock'])
            ->withSum([
                'order_items as total_sold'
            ], 'quantity')
            ->withCount('reviews')->withAvg('reviews', 'rating')
            ->filters(request(['availability', 'sort', 'categories', 'max_price', 'min_price', 'stock', 'rating'])))
            ->paginate(10);
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
}
