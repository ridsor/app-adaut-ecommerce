<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ItemNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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
                $query->select(['quantity', 'product_id', 'order_id']);
            },
            'order_items.product' => function ($query) {
                $query->select(['name', 'id', 'image', 'slug']);
            },
            'order_items.product.reviews' => fn($query) => $query->whereHas('order', function ($query) use ($order_number) {
                $query->where('order_number', $order_number);
            }),
            'order_items.product.reviews.review_media' => fn($query) => $query->select('review_id', 'file_path')
        ])->where('user_id', $request->user()->id)->where('order_number', $order_number)->where('status', 'completed')->first();

        if (!$order_review) {
            throw new ItemNotFoundException($order_number);
        }

        return view('user.order.review.index', [
            'title' => 'Penilaian Produk',
            'order_review' => $order_review,
            'user' => $request->user()
        ]);
    }

    public function productShow(Request $request, $order_number, $slug)
    {
        $product = Product::select(['id', 'image'])->where('slug', $slug)->first();

        $review = Review::with([
            'review_media' => fn($query) => $query->select('review_id', 'file_path', 'id')
        ])->whereHas('product', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->whereHas('order', function ($query) use ($order_number) {
            $query->where('order_number', $order_number)->where('status', 'completed');
        })
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$product) {
            throw new ItemNotFoundException('Penilaian produk');
        }

        return view('user.order.review.edit', [
            'title' => 'Penilaian Produk',
            'header_url' => route('user.review.product.index', ['order_number' => $order_number]),
            'review' => $review,
            'product' => $product
        ]);
    }

    public function productUpdate(Request $request, $order_number, $slug)
    {
        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:1048',
            'deletedFiles' => 'nullable|array|max:5',
            'deletedFiles.*' => 'nullable|exists:review_media,id'
        ];

        $validated = $request->validate($rules);

        try {
            $order = Order::select('id')->where('user_id', $request->user()->id)->where('order_number', $order_number)->where('status', 'completed')->firstOrFail();
            $product = Product::select('id')->where('slug', $slug)->firstOrFail();


            $review = $request->user()->reviews()->updateOrCreate([
                'order_id' => $order->id,
                'product_id' => $product->id
            ], [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            // Hapus file yang dipilih user
            if ($request->has('deletedFiles')) {
                foreach ($request->deletedFiles as $fileId) {
                    $media = ReviewMedia::find($fileId);
                    if ($media && $media->review_id === $review->id) {
                        Storage::delete($media->file_path);
                        $media->delete();
                    }
                }
            }

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $image) {
                    $path = $image->store('foto/ulasan');
                    $review->review_media()->create([
                        'file_path' => $path,
                        'type' => 'photo'
                    ]);
                }
            }

            return redirect(route('user.review.product.index', ['order_number' => $order_number]));
        } catch (\Exception $e) {
            Log::info($e);
            return back()->with('error', 'Gagal menilai produk');
        }
    }
}
