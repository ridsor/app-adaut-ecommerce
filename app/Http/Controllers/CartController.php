<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function changeQuantity(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(),[
                "quantity" => "required|integer",
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Validasi gagal',
                    "errors" => $validator->errors()
                ], 500);
            }
            
            $cart = Cart::find($id);
            if (!$cart) {
                return response()->json([
                    'status' => "fail",
                    "message" => "Keranjang tidak ditemukan"
                ], 404);
            }
            if (!Gate::forUser(Auth::user())->allows('owner', $cart)) {
                return response()->json([
                    'status' => "fail",
                    "message" => "Anda tidak berwenang untuk mengakses keranjang ini"
                ], 403);
            }
            
            $cart->changeQuantity($request->quantity);
    
            return response()->json([
                "status" => "success",
                "message" => "Berhasil memperbarui kuantitas"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Gagal memperbarui kuantitas',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('isUser');

            $request->validate(
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

            $product = Product::where("id",$request->product_id)->first();
            if(!$product) {
                return Response::denyAsNotFound(
                    "Produk tidak ditemukan"
                );
            }

            Cart::create([
                "product_id" => $product->id,
                "user_id" => Auth::user()->id,
                "quantity" => $request->quantity
            ]);

            return back()->with([
                "status" => "success",
                "message" => "Berhasil menambahkan produk ke keranjang"
            ]);
        } catch (\Exception $e) {
            return back()->with([
                "status" => "fail",
                "message" => "Gagal menambahkan produk ke keranjang",
            ]);
        }
    }

    public function destroy(array $id)
    {
        try {
            foreach($id as $item) {
                $cart = Cart::find($item);
                $this->authorize('owner', $cart);
                $cart->delete();
            }
    
            return back()->with([
                "status" => "success",
                "message" => "Berhasil menghapus item keranjang"
            ]);
        } catch (\Exception $e) {
            return back()->with([
                "status" => "fail",
                "message" => "Gagal menghapus item keranjang",
            ]);
        }
    }
}
