<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $bestsellers = Product::select("image","price", "id")
                ->withSum([
                    'order_items as total_sold'
                ], 'quantity')
                ->withCount('reviews')->withAvg('reviews', 'rating')
                ->orderByDesc('total_sold')
                ->limit(6)->get();
        $foods = Product::select("image","price", "id")
                ->withSum([
                    'order_items as total_sold'
                ], 'quantity')
                ->withCount('reviews')->withAvg('reviews', 'rating')
                ->orderByDesc('total_sold')
                ->whereHas("category", function ($query) {
                    $query->where('name', 'Makanan');
                })
                ->limit(8)->get();
        $crafts = Product::select("image","price", "id")
                ->withSum([
                    'order_items as total_sold'
                ], 'quantity')
                ->withCount('reviews')->withAvg('reviews', 'rating')
                ->whereHas("category", function ($query) {
                    $query->where('name', 'Kerajinan');
                })
                ->limit(8)->get();
        $categories = Category::select('name','icon','id')->get();
        

        return view('home', [
            'bestsellers' => $bestsellers,
            'foods' => $foods,
            'crafts' => $crafts,
            'categories' => $categories,
            'title' => 'Home',
        ]);
    }
}
