<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentNotification extends BaseController
{

    public function __invoke(Request $request)
    {
        try {
            $isValid = $this->validateDokuSignature($request);

            if (!$isValid) {
                return $this->sendError("Invalid signature", 403);
            }

            $notification = $request->all();

            $invoice = $notification['order']['invoice_number'] ?? null;
            $status = $notification['transaction']['status'] ?? null;
            $payment_method = $notification['channel']['id'] ?? null;
            $amount = $notification['order']['amount'] ?? null;

            $transaction = Transaction::where('invoice', $invoice)->first();

            $transaction->payment()->create([
                'status' => $status,
                'payment_method' => $payment_method,
                'amount' => $amount,
            ]);

            if ($status === 'SUCCESS') {
                $transaction->order()->update([
                    'status' => 'packed',
                ]);
            } else if ($status === 'FAILED') {
                $transaction->order()->update([
                    'status' => 'failed',
                ]);
            } else if ($status === 'EXPIRED') {
                $transaction->order()->update([
                    'status' => 'failed',
                ]);
            }



            return response()->json(['message' => 'Notification processed successfully'], 200);
        } catch (\Exception $e) {
            return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
        }
    }

    function generateSignature($clientId, $requestId, $requestTarget, $digest, $secret, $requestTimestamp)
    {
        // Prepare Signature Component
        $componentSignature = "Client-Id:" . $clientId;
        $componentSignature .= "\n";
        $componentSignature .= "Request-Id:" . $requestId;
        $componentSignature .= "\n";
        $componentSignature .= "Request-Timestamp:" . $requestTimestamp;
        $componentSignature .= "\n";
        $componentSignature .= "Request-Target:" . $requestTarget;

        if ($digest) {
            $componentSignature .= "\n";
            $componentSignature .= "Digest:" . $digest;
        }

        // Calculate HMAC-SHA256
        $hmac = hash_hmac('sha256', $componentSignature, $secret, true);
        $signature = base64_encode($hmac);

        return "HMACSHA256=" . $signature;
    }

    function generateDigest($body)
    {
        $hash = hash('sha256', $body, true);
        return base64_encode($hash);
    }

    protected function validateDokuSignature(Request $request): bool
    {
        $secretKey = config('doku.secret_key');
        $clientId = $request->header('client_id');

        $requestId = $request->header('request-id');
        $requestTimestamp = $request->header('request-timestamp');
        $signatureHeader = $request->header('signature');

        $targetPath = "/api/payments/notifications";
        $rawBody = $request->getContent();
        $digest = $this->generateDigest($rawBody);

        $signature = $this->generateSignature(
            $clientId,
            $requestId,
            $targetPath,
            $digest,
            $secretKey,
            $requestTimestamp
        );

        return $signature === $signatureHeader;
    }
}
