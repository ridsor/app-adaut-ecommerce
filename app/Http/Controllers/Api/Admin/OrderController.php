<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use Illuminate\Http\Request;
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
                Order::where('order_number', $item['order_number'])->update(['status' => $validated['status']]);
            }

            return $this->sendResponse("Berhasil memperbarui pesanan");
        } catch (\Exception $e) {
            return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
        }
    }
}
