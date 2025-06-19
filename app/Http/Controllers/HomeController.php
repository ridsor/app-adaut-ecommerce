<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $bestsellers = Product::select("id", "image", "price", "name", "slug", "stock")
            ->withSum([
                'order_items as total_sold'
            ], 'quantity')
            ->withCount('reviews')->withAvg('reviews', 'rating')
            ->orderBy('total_sold', 'desc')
            ->limit(10)->get();
        $products = Product::select("id", "image", "price", "name", "slug", "stock")
            ->withSum([
                'order_items as total_sold'
            ], 'quantity')
            ->withCount('reviews')->withAvg('reviews', 'rating')
            ->latest()
            ->limit(8)->get();
        $categories = Category::select(['name', 'id', 'slug', 'icon'])->withCount('products')->get();
        $banners = Banner::select('title', 'description', 'button_text', 'button_link', 'image')->latest()->get();

        return view('home', [
            'bestsellers' => $bestsellers,
            'products' => $products,
            'categories' => $categories,
            'title' => 'Home',
            'banners' => $banners,
        ]);
    }
}
