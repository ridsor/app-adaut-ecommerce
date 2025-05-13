<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $bestsellers = Product::select("id","image","price","name", "slug")
                ->withSum([
                    'order_items as total_sold'
                ], 'quantity')
                ->withCount('reviews')->withAvg('reviews', 'rating')
                ->orderByDesc('total_sold')
                ->limit(10)->get();
        $products = Product::select("id","image","price", "name", "slug")
                ->withSum([
                    'order_items as total_sold'
                ], 'quantity')
                ->withCount('reviews')->withAvg('reviews', 'rating')
                ->latest()
                ->limit(10)->get();
        $categories = Category::select('name','icon','id')->get();
        

        return view('home', [
            'bestsellers' => $bestsellers,
            'products' => $products,
            'categories' => $categories,
            'title' => 'Home',
            'banners' => [],
        ]);
    }
}
