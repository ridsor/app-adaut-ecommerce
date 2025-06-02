<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ItemNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->authorize('isUser');
    }

    public function productIndex(Request $request, $order_number)
    {
        $order_review = Order::with([
            'order_items' => function ($query) {
                $query->select(['quantity', 'product_id', 'order_id'])->limit(1);
            },
            'order_items.product' => function ($query) {
                $query->select(['name', 'id', 'image', 'slug']);
            },
            'order_items.product.reviews' => fn($query) => $query->select('id', 'user_id', 'product_id', 'order_id'),
            'order_items.product.reviews.user' => fn($query) => $query->select('id', 'username', 'image')
        ])->where('user_id', $request->user()->id)->where('order_number', $order_number)->first();

        if (!$order_review) {
            throw new ItemNotFoundException($order_number);
        }

        return view('user.order.review.index', [
            'title' => 'Penilaian Produk',
            'order_review' => $order_review
        ]);
    }

    public function productShow(Request $request, $order_number, $slug)
    {
        $product = Product::select(['id', 'image'])->where('slug', $slug)->first();

        $review = Review::whereHas('product', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->whereHas('order', function ($query) use ($order_number) {
            $query->where('order_number', $order_number);
        })
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$product) {
            throw new ItemNotFoundException('Penilaian produk');
        }

        return view('user.order.review.show', [
            'title' => 'Penilaian Produk',
            'header_url' => route('user.review.product.index', ['order_number' => $order_number]),
            'review' => $review,
            'product' => $product
        ]);
    }

    public function productUpdate(Request $request, $order_number, $slug)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|max:5',
            'comment' => 'required|string',
            'photos' => 'array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:1048',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $image) {
                $path = $image->store('foto/ulasan');
                // $->media()->create([
                //     'file_path' => $path,
                //     'type' => 'image'
                // ]);
            }
        }
    }
}
