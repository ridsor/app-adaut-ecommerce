<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ItemNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorize('isUser');
    }

    public function index(Request $request)
    {
        $orders = Order::search($request->query('search'))->query(
            fn($query) =>
            $query->select(['id', 'order_number', 'user_id', 'status', 'amount'])
                ->with([
                    'order_items' => function ($query) {
                        $query->select(['quantity', 'product_id', 'order_id']);
                    },
                    'transaction' => function ($query) {
                        $query->select(['order_id', 'url']);
                    },
                    'order_items.product' => function ($query) {
                        $query->select(['name', 'price', 'id', 'image']);
                    }
                ])
                ->where('user_id', $request->user()->id)->latest()
        )->paginate(10);

        return view('user.order.index', [
            'title' => 'Pesanan Saya',
            'header_title' => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }
    public function unpaid(Request $request)
    {
        $orders = Order::search($request->query('search'))->query(
            fn($query) =>
            $query->select(['id', 'order_number', 'user_id', 'status', 'amount'])
                ->with([
                    'order_items' => function ($query) {
                        $query->select(['quantity', 'product_id', 'order_id'])->limit(1);
                    },
                    'transaction' => function ($query) {
                        $query->select(['order_id', 'url']);
                    },
                    'order_items.product' => function ($query) {
                        $query->select(['name', 'price', 'id', 'image']);
                    }
                ])->where('user_id', $request->user()->id)->where('status', 'unpaid')->latest()
        )->paginate(10);

        return view('user.order.unpaid', [
            'title' => 'Pesanan Saya',
            'header_title' => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }
    public function packed(Request $request)
    {
        $orders = Order::search($request->query('search'))->query(
            fn($query) =>
            $query->select(['id', 'order_number', 'user_id', 'status', 'amount'])
                ->with([
                    'order_items' => function ($query) {
                        $query->select(['quantity', 'product_id', 'order_id'])->limit(1);
                    },
                    'transaction' => function ($query) {
                        $query->select(['order_id', 'url']);
                    },
                    'order_items.product' => function ($query) {
                        $query->select(['name', 'price', 'id', 'image']);
                    }
                ])->where('user_id', $request->user()->id)->where('status', 'packed')->latest()
        )->paginate(10);

        return view('user.order.packed', [
            'title' => 'Pesanan Saya',
            'header_title' => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }
    public function submitted(Request $request)
    {
        $orders = Order::search($request->query('search'))->query(
            fn($query) =>
            $query->select(['id', 'order_number', 'user_id', 'status', 'amount'])
                ->with([
                    'order_items' => function ($query) {
                        $query->select(['quantity', 'product_id', 'order_id'])->limit(1);
                    },
                    'transaction' => function ($query) {
                        $query->select(['order_id', 'url']);
                    },
                    'order_items.product' => function ($query) {
                        $query->select(['name', 'price', 'id', 'image']);
                    }
                ])->where('user_id', $request->user()->id)->where('status', 'submitted')->latest()
        )->paginate(10);

        return view('user.order.submitted', [
            'title' => 'Pesanan Saya',
            'header_title' => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }
    public function completed(Request $request)
    {
        $orders = Order::search($request->query('search'))->query(
            fn($query) =>
            $query->select(['id', 'order_number', 'user_id', 'status', 'amount'])
                ->with([
                    'order_items' => function ($query) {
                        $query->select(['quantity', 'product_id', 'order_id'])->limit(1);
                    },
                    'transaction' => function ($query) {
                        $query->select(['order_id', 'url']);
                    },
                    'order_items.product' => function ($query) {
                        $query->select(['name', 'price', 'id', 'image']);
                    }
                ])->where('user_id', $request->user()->id)->where('status', 'completed')->latest()
        )->paginate(10);

        return view('user.order.completed', [
            'title' => 'Pesanan Saya',
            'header_title' => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }
    public function failed(Request $request)
    {
        $orders = Order::search($request->query('search'))->query(
            fn($query) =>
            $query->select(['id', 'order_number', 'user_id', 'status', 'amount'])
                ->with([
                    'order_items' => function ($query) {
                        $query->select(['quantity', 'product_id', 'order_id'])->limit(1);
                    },
                    'transaction' => function ($query) {
                        $query->select(['order_id', 'url']);
                    },
                    'order_items.product' => function ($query) {
                        $query->select(['name', 'price', 'id', 'image']);
                    }
                ])->where('user_id', $request->user()->id)->where('status', 'failed')->latest()
        )->paginate(10);

        return view('user.order.failed', [
            'title' => 'Pesanan Saya',
            'header_title' => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, $order_number)
    {
        $order = Order::with(['order_items', 'order_items.product', 'transaction', 'shipping', 'transaction.payment'])
            ->withSum([
                'order_items as total_price' => function ($query) {
                    $query->select(DB::raw('SUM(order_items.quantity * products.price)'))
                        ->join('products', 'order_items.product_id', '=', 'products.id');
                }
            ], '')->where('user_id', $request->user()->id)->where('order_number', $order_number)->first();

        if (!$order) {
            throw new ItemNotFoundException($order_number);
        }

        return view('user.order.show', [
            'title' => 'Pesanan ' . $order->order_number,
            'header_title' => $order->order_number,
            "header_url" => route('user.order.index'),
            'order' => $order,
        ]);
    }
}
