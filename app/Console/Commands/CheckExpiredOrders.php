<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CheckExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-expired';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tandai order yang melewati batas waktu sebagai expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = Order::select('id')->with([
            'order_items' => fn($query) => $query->select(['product_id', 'order_id', 'quantity']),
            'order_items.product' => fn($query) => $query->withTrashed()->select('id', 'stock'),
        ])->where('status', 'unpaid')
            ->whereHas('transaction', function ($q) {
                $q->where('expired_at', '<=', now());
            })
            ->get();

        foreach ($expiredOrders as $order) {
            $order->status = 'failed';
            $order->restoringProductStock();
            $order->save();
        }

        $this->info(count($expiredOrders) . ' order ditandai sebagai expired.');
    }
}
