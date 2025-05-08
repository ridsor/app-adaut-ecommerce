<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function changeQuantity(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            "quantity" => "required|integer",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Validation failed',
                "errors" => $validator->errors()
            ], 500);
        }

        $cart = Cart::find($id);
        if ($cart) {
            return response()->json([
                'status' => "fail",
                "message" => "Cart not found"
            ], 404);
        }

        $cart->changeQuantity($request->quantity);

        return response()->json([
            "status" => "success",
            "message" => "Berhasil memperbarui kuantitas"
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'product_id' => 'required',
                    'quantity' => 'required|integer|min:1',
                ],
                [
                    'product_id.required' => 'ID produk wajib diisi',
                    'quantity.required' => 'Jumlah produk wajib diisi',
                    'quantity.integer' => 'Jumlah produk harus berupa angka bulat',
                    'quantity.min' => 'Jumlah produk minimal harus 1',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Validation failed',
                    "errors" => $validator->errors()
                ], 500);
            }

            $product = Product::where("id",$request->product_id)->first();
            if(!$product) {
            return response()->json([
                "status" => "error",
                "message" => "Product not found",
            ], 404);
            }

            Cart::create([
                "product_id" => $product->id,
                "user_id" => Auth::user()->id,
                "quantity" => $request->quantity
            ]);

            return response()->json([ 
                "status" => "success",
                "message" => "Berhasil ditambahkan ke keranjang",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Something went wrong",
            ], 500);
        }
    }

    public function destroy(array $id)
    {
        foreach($id as $item) {
            Cart::destroy($item);
        }

        return response()->json([ 
            "status" => "success",
            "message" => "Berhasil menghapus item keranjang",
        ]);
    }
}
