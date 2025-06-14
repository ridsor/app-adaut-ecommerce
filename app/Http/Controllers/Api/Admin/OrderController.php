<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->authorize("isAdmin");
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'status' => 'required|in:failed,packed',
                    'items' => 'required|array',
                    'items.*.order_number' => ['required', Rule::exists('orders', 'order_number')],
                ],
                [
                    'items.*.order_number.required' => 'Order Number wajib diisi.',
                ]
            );
            if ($validator->fails()) {
                return $this->sendError(error: 'Validasi gagal', errorMessages: $validator->errors(), code: 500);
            }

            $validated = $validator->validated();

            foreach ($validated['items'] as $item) {
                Order::select('id')->where('order_number', $item['order_number'])->update(['status' => $validated['status']]);

                if ($validated['status'] === 'failed') {
                    $order = Order::select('id')->with([
                        'order_items' => fn($query) => $query->select(['product_id', 'order_id', 'quantity']),
                        'order_items.product' => fn($query) => $query->withTrashed()->select('id', 'stock'),
                    ])
                        ->where('order_number', $item['order_number'])
                        ->first();
                    $order->restoringProductStock();
                    $order->save();
                }
            }

            return $this->sendResponse("Berhasil memperbarui pesanan");
        } catch (\Exception $e) {
            Log::info($e);
            return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
        }
    }
}
