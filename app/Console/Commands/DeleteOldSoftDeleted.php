<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;

class DeleteOldSoftDeleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:delete-soft-deletes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force delete soft-deleted data older if they have no comments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::onlyTrashed()
            ->doesntHave('order_items')
            ->get();
        $users = User::onlyTrashed()
            ->doesntHave('orders')
            ->doesntHave('reviews')
            ->get();

        $deletedCount = 0;

        foreach ($products as $product) {
            $product->forceDelete();
            $deletedCount++;
        }
        foreach ($users as $user) {
            $user->forceDelete();
            $deletedCount++;
        }

        $this->info("Deleted {$deletedCount} old soft-deleted data.");
    }
}
