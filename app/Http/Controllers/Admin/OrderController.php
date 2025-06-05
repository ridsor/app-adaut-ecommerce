<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ItemNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorize('isAdmin');
    }

    public function index(Request $request)
    {
        $total_items = Order::count();
        $status = Order::select(['status', DB::raw('count(*) as total')])
            ->groupBy('status')
            ->get();

        $courir = Shipping::select(['name', DB::raw('count(*) as total')])->groupBy('name')->get();

        $sort = [
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
        ];

        $orders = Order::search($request->query('search'))->query(
            fn($query) =>
            $query->select(['id', 'order_number', 'status', 'amount', 'user_id'])
                ->filters(request(['sort', 'status', 'courir']))
                ->with([
                    'order_items' => function ($query) {
                        $query->select(['quantity', 'product_id', 'order_id'])->limit(1);
                    },
                    'transaction' => function ($query) {
                        $query->select(['order_id', 'url']);
                    },
                    'order_items.product' => function ($query) {
                        $query->withTrashed()->select(['name', 'price', 'id', 'image']);
                    },
                    'user' => fn($query) => $query->withTrashed()->select(['name', 'id']),
                    'shipping' => fn($query) => $query->select(['order_id', 'name']),
                ])->withCount('order_items')
        )->paginate(10);

        return view('admin.order.index', [
            'title' => 'Pesanan',
            'total_items' => $total_items,
            'sort' => $sort,
            'status' => $status,
            'courir' => $courir,
            'orders' => $orders
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $order_number)
    {
        $order = Order::with([
            'order_items',
            'order_items.product' => fn($query) => $query->withTrashed()->select('id', 'name', 'price', 'image'),
            'transaction',
            'shipping',
            'transaction.payment',
            'user' => fn($query) => $query->withTrashed()->select('id', 'name', 'email')
        ])
            ->withSum([
                'order_items as total_price' => function ($query) {
                    $query->select(DB::raw('SUM(order_items.quantity * products.price)'))
                        ->join('products', 'order_items.product_id', '=', 'products.id');
                }
            ], '')->where('order_number', $order_number)->first();

        if (!$order) {
            throw new ItemNotFoundException($order_number);
        }

        return view('admin.order.show', [
            'title' => 'Pesanan ' . $order->order_number,
            'header_title' => $order->order_number,
            "header_url" => route('admin.order.index'),
            'order' => $order,
        ]);
    }
}