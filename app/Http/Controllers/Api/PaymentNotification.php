<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Midtrans\Config;

class PaymentNotification extends BaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $serverKey = config('midtrans.server_key');
            $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
            if ($hashedKey !== $request->signature_key) {
                return response()->json(['message' => 'Invalid signature key'], 403);
            }

            $transaction_status = $request->transaction_status;
            $type = $request->payment_type;
            $invoice = $request->order_id;
            $fraud = $request->fraud_status;

            if ($transaction_status == 'capture') {
                // For credit card transaction, we need to check whether transaction is challenge by FDS or not
                if ($fraud == 'accept') {
                    // TODO set payment status in merchant's database to 'Success'
                    echo "Transaction invoice: " . $invoice . " successfully captured using " . $type;

                    $payment_date = Carbon::parse($request->transaction_time);
                    $payment_status = "success";
                }
            } else if ($transaction_status == 'settlement') {
                // TODO set payment status in merchant's database to 'Settlement'
                echo "Transaction invoice: " . $invoice . " successfully transfered using " . $type;

                $payment_date = Carbon::parse($request->transaction_time);
                $payment_status = "success";
            } else if ($transaction_status == 'pending') {
                // TODO set payment status in merchant's database to 'Pending'
                echo "Waiting customer to finish transaction invoice: " . $invoice . " using " . $type;

                $payment_status = "pending";
            } else if ($transaction_status == 'deny') {
                // TODO set payment status in merchant's database to 'Denied'
                echo "Payment using " . $type . " for transaction invoice: " . $invoice . " is denied.";

                $payment_status = "failed";
            } else if ($transaction_status == 'expire') {
                // TODO set payment status in merchant's database to 'expire'
                echo "Payment using " . $type . " for transaction invoice: " . $invoice . " is expired.";

                $payment_status = "expired";
            } else if ($transaction_status == 'cancel') {
                // TODO set payment status in merchant's database to 'Denied'
                echo "Payment using " . $type . " for transaction invoice: " . $invoice . " is canceled.";

                $payment_status = "failed";
            }

            $transaction = Transaction::where('transaction_id', $invoice)->first();

            $transaction->payment()->create([
                "status" => $payment_status,
                "payment_date" => $payment_date,
                "payment_method" => $request->payment_type,
                "amount" => $request->gross_amount,
            ]);

            return response()->json(['message' => 'Notifikasi telah berhasil ditangani'], 200);
        } catch (\Exception $e) {
            return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
        }
    }
}
