<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    public function index(Request $request, $product)
    {
        $perPage = $request->get('per_page', 10); // Default 10 item per halaman
        $page = $request->get('page', 1); // Default halaman 1

        $reviews = Review::filter(request(['rating']))->with([
            'user' => function ($query) {
                $query->withTrashed()->select(['username', 'id']);
            },
            'review_media' => fn($query) => $query->select(['review_id', 'file_path', 'id'])
        ])->whereHas('product', fn($query) => $query->where('slug', $product))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $reviews->items(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }
}
