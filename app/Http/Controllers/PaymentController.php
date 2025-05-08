<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Midtrans\Config;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function notificationHandler(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');
            $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
            if ($hashedKey !== $request->signature_key) {
                return response()->json(['message' => 'Invalid signature key'], 403);
            }

            $transaction_status = $request->transaction_status;
            $type = $request->payment_type;
            $order_id = $request->order_id;
            $fraud = $request->fraud_status;


            $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();

            if (!$transaction) {
                $transaction = Transaction::create([
                    "transaction_id" => $request->transaction_id,
                    "order_id" => $request->order_id,
                    "payment_method" => $request->payment_type,
                    "amount" => $request->gross_amount,
                ]);
            }

            if ($transaction_status == 'capture') {
                // For credit card transaction, we need to check whether transaction is challenge by FDS or not
                if ($fraud == 'accept') {
                    // TODO set payment status in merchant's database to 'Success'
                    echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;

                    $transaction->payment_date = Carbon::parse($request->transaction_time);
                    $transaction->status = "success";
                }
            } else if ($transaction_status == 'settlement') {
                // TODO set payment status in merchant's database to 'Settlement'
                echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;

                $transaction->payment_date = Carbon::parse($request->transaction_time);
                $transaction->status = "success";
            } else if ($transaction_status == 'pending') {
                // TODO set payment status in merchant's database to 'Pending'
                echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;

                $transaction->status = "pending";
            } else if ($transaction_status == 'deny') {
                // TODO set payment status in merchant's database to 'Denied'
                echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";

                $transaction->status = "failed";
            } else if ($transaction_status == 'expire') {
                // TODO set payment status in merchant's database to 'expire'
                echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";

                $transaction->status = "expired";
            } else if ($transaction_status == 'cancel') {
                // TODO set payment status in merchant's database to 'Denied'
                echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";

                $transaction->status = "failed";
            }

            $transaction->save();

            return response()->json(['message' => 'Notifikasi telah berhasil ditangani'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "status" => "error",
                "message" => "Something went wrong",
            ], 500);
        }
    }
}
